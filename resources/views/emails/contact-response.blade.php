<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response to Your Inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #6f42c1, #fd7e14);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .original-message {
            background: white;
            padding: 20px;
            border-left: 4px solid #6f42c1;
            margin: 20px 0;
            border-radius: 4px;
        }
        .response {
            background: white;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">⭐ {{ $settings['site_name'] }}</div>
        <p>Thank you for contacting us</p>
    </div>
    
    <div class="content">
        <h2>Dear {{ $contact->name }},</h2>
        
        <p>Thank you for reaching out to us. We have reviewed your inquiry and are pleased to provide the following response:</p>
        
        <div class="response">
            <h3>Our Response:</h3>
            {!! nl2br(e($contact->admin_response)) !!}
        </div>
        
        <div class="original-message">
            <h4>Your Original Message:</h4>
            <p><strong>Subject:</strong> {{ $contact->subject }}</p>
            <p><strong>Message:</strong></p>
            <p>{{ $contact->message }}</p>
            <p><small><strong>Sent:</strong> {{ $contact->created_at->format('F j, Y \a\t g:i A') }}</small></p>
        </div>
        
        <p>If you have any further questions or need additional assistance, please don't hesitate to contact us again. We're here to help!</p>
        
        <p>Best regards,<br>
        The {{ $settings['site_name'] }} Team</p>
    </div>
    
    <div class="footer">
        <p>This email was sent in response to your inquiry submitted through our website.</p>
        <p>© {{ date('Y') }} {{ $settings['site_name'] }}. All rights reserved.</p>
    </div>
</body>
</html>