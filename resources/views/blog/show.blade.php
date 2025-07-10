@extends('layouts.app')

@section('title', $blog->meta_title ?? $blog->title . ' - Divine Astrology')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <article>
                @if($blog->featured_image)
                <img src="{{ asset($blog->featured_image) }}" class="img-fluid rounded mb-4" alt="{{ $blog->title }}">
                @endif
                
                <h1 class="mb-3">{{ $blog->title }}</h1>
                <p class="text-muted mb-4">
                    <i class="far fa-calendar me-2"></i>{{ $blog->published_at->format('F d, Y') }}
                </p>
                
                <div class="blog-content">
                    {!! $blog->content !!}
                </div>
            </article>
            
            @if($relatedBlogs->count() > 0)
            <hr class="my-5">
            
            <h3 class="mb-4">Related Articles</h3>
            <div class="row">
                @foreach($relatedBlogs as $related)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">{{ $related->title }}</h6>
                            <a href="{{ route('blog.show', $related->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.blog-content {
    font-size: 1.1rem;
    line-height: 1.8;
}
.blog-content h2 {
    margin-top: 2rem;
    margin-bottom: 1rem;
}
.blog-content p {
    margin-bottom: 1.5rem;
}
</style>
@endpush
@endsection

