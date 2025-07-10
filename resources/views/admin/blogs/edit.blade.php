<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Blog Post') }}
            </h2>
            <x-secondary-button onclick="window.location.href='{{ route('admin.blogs.index') }}'">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </x-secondary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form id="blog-form" method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Main Content Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Blog Content</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $blog->title)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="excerpt" :value="__('Excerpt')" />
                                <textarea id="excerpt"
                                          name="excerpt"
                                          rows="3"
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                          required>{{ old('excerpt', $blog->excerpt) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('excerpt')" />
                            </div>

                            <div>
                                <x-input-label for="content" :value="__('Content')" />
                                <div id="content-editor" style="height: 300px;" class="mt-1 border border-gray-300 rounded-md"></div>
                                <textarea id="content" name="content" style="display: none;">{{ old('content', $blog->content) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            <div>
                                <x-input-label for="featured_image" :value="__('Featured Image')" />
                                @if($blog->featured_image)
                                    <div class="mb-2">
                                        <p class="text-sm text-gray-600">Current image: {{ $blog->featured_image }}</p>
                                    </div>
                                @endif
                                <input type="file"
                                       id="featured_image"
                                       name="featured_image"
                                       accept="image/*"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="meta_title" :value="__('Meta Title')" />
                                <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title', $blog->meta_title)" />
                                <x-input-error class="mt-2" :messages="$errors->get('meta_title')" />
                            </div>

                            <div>
                                <x-input-label for="meta_description" :value="__('Meta Description')" />
                                <textarea id="meta_description"
                                          name="meta_description"
                                          rows="2"
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('meta_description')" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Publishing Options Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing Options</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status"
                                        name="status"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required>
                                    <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div>
                                <x-input-label for="published_at" :value="__('Publish Date')" />
                                <input type="datetime-local"
                                       id="published_at"
                                       name="published_at"
                                       value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <x-input-error class="mt-2" :messages="$errors->get('published_at')" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <x-primary-button>
                        {{ __('Update Blog Post') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <!-- Quill.js CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    @endpush

    @push('scripts')
    <!-- Quill.js JavaScript -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
    // Initialize Quill editor
    var quill = new Quill('#content-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'blockquote', 'code-block'],
                ['clean']
            ]
        },
        placeholder: 'Write your blog content here...'
    });

    // Set initial content
    @if(old('content', $blog->content))
        quill.root.innerHTML = {!! json_encode(old('content', $blog->content)) !!};
    @endif

    // Sync Quill content with hidden textarea on form submit and validate
    document.getElementById('blog-form').addEventListener('submit', function(e) {
        var content = quill.root.innerHTML;
        var textContent = quill.getText().trim();
        
        // Update hidden textarea
        document.getElementById('content').value = content;
        
        // Custom validation for content
        if (textContent.length === 0) {
            e.preventDefault();
            alert('Please enter some content for your blog post.');
            quill.focus();
            return false;
        }
    });

    // Update hidden field whenever content changes for real-time sync
    quill.on('text-change', function() {
        document.getElementById('content').value = quill.root.innerHTML;
    });
    </script>
    @endpush
</x-app-layout>