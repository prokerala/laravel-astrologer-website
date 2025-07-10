@extends('layouts.app')

@section('title', 'Astrology Blog - Divine Astrology')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">Astrology Blog</h1>
    
    <div class="row">
        @forelse($blogs as $blog)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($blog->featured_image)
                <img src="{{ asset($blog->featured_image) }}" class="card-img-top" alt="{{ $blog->title }}">
                @else
                <div class="bg-light p-5 text-center">
                    <i class="fas fa-star fa-3x text-muted"></i>
                </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $blog->title }}</h5>
                    <p class="card-text">{{ $blog->excerpt }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-sm btn-primary">Read More</a>
                        <small class="text-muted">{{ $blog->published_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-center text-muted">No blog posts available yet.</p>
        </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $blogs->links() }}
    </div>
</div>
@endsection

