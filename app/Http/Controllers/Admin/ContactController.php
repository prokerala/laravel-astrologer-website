<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactResponse;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $query = Contact::with('respondedBy')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by read status
        if ($request->filled('read_status')) {
            if ($request->read_status === 'unread') {
                $query->unread();
            } elseif ($request->read_status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter by response status
        if ($request->filled('response_status')) {
            if ($request->response_status === 'responded') {
                $query->responded();
            } elseif ($request->response_status === 'unresponded') {
                $query->unresponded();
            }
        }

        $contacts = $query->paginate(20);

        // Get counts for dashboard
        $counts = [
            'total' => Contact::count(),
            'unread' => Contact::unread()->count(),
            'unresponded' => Contact::unresponded()->count(),
            'new' => Contact::where('status', 'new')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'counts'));
    }

    public function show(Contact $contact): View
    {
        // Mark as read when viewing
        if (!$contact->is_read) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function updateStatus(Request $request, Contact $contact): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,resolved'
        ]);

        $contact->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Contact status updated successfully.');
    }

    public function respond(Request $request, Contact $contact): RedirectResponse
    {
        $request->validate([
            'admin_response' => 'required|string|max:2000',
            'send_email' => 'boolean'
        ]);

        // Update contact with response
        $contact->update([
            'admin_response' => $request->admin_response,
            'responded_at' => now(),
            'responded_by' => auth()->id(),
            'status' => 'resolved',
            'is_read' => true
        ]);

        // Send email if requested
        if ($request->boolean('send_email')) {
            try {
                Mail::to($contact->email)->send(new ContactResponse($contact));
                $message = 'Response saved and email sent successfully.';
            } catch (\Exception $e) {
                $message = 'Response saved but email failed to send: ' . $e->getMessage();
            }
        } else {
            $message = 'Response saved successfully.';
        }

        return redirect()->route('admin.contacts.show', $contact)
            ->with('success', $message);
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact message deleted successfully.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete,change_status',
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id',
            'status' => 'required_if:action,change_status|in:new,in_progress,resolved'
        ]);

        $contacts = Contact::whereIn('id', $request->contact_ids);

        switch ($request->action) {
            case 'mark_read':
                $contacts->update(['is_read' => true]);
                $message = 'Selected contacts marked as read.';
                break;
            case 'mark_unread':
                $contacts->update(['is_read' => false]);
                $message = 'Selected contacts marked as unread.';
                break;
            case 'delete':
                $count = $contacts->count();
                $contacts->delete();
                $message = "Deleted {$count} contacts.";
                break;
            case 'change_status':
                $contacts->update(['status' => $request->status]);
                $message = 'Status updated for selected contacts.';
                break;
        }

        return redirect()->route('admin.contacts.index')->with('success', $message);
    }
}