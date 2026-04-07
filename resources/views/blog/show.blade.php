@extends('layouts.app')

@section('content')
    @include('layouts.navigation')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">Accueil</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-700">Blog</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-600">{{ $article->title ?? 'Article' }}</li>
            </ol>
        </nav>

        @if(isset($article))
        <!-- Article Header -->
        <header class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <span class="px-4 py-2 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                    {{ $article->category ?? 'Article' }}
                </span>
                <span class="text-gray-500">
                    <i class="ri-calendar-line mr-1"></i>
                    {{ $article->created_at->format('d F Y') }}
                </span>
                <span class="text-gray-500">
                    <i class="ri-time-line mr-1"></i>
                    {{ $article->read_time ?? '5' }} min
                </span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>
            @if($article->excerpt)
            <p class="text-xl text-gray-600">{{ $article->excerpt }}</p>
            @endif
            
            @if($article->author)
            <div class="flex items-center mt-6 pt-6 border-t border-gray-200">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mr-4">
                    <span class="text-white font-bold text-lg">{{ substr($article->author, 0, 1) }}</span>
                </div>
                <div>
                    <div class="font-semibold text-gray-900">{{ $article->author }}</div>
                    <div class="text-sm text-gray-500">Auteur</div>
                </div>
            </div>
            @endif
        </header>

        <!-- Article Featured Image -->
        @if(isset($article->image))
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-96 object-cover" loading="lazy">
        </div>
        @else
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg h-96 flex items-center justify-center mb-8">
            <i class="ri-article-line text-white text-9xl opacity-20"></i>
        </div>
        @endif

        <!-- Article Content -->
        <article class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="prose prose-lg max-w-none">
                {!! $article->content !!}
            </div>
        </article>

        <!-- Article Tags -->
        @if(isset($article->tags) && count($article->tags) > 0)
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm">
                    #{{ $tag }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Share Buttons -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Partager cet article</h3>
            <div class="flex space-x-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="ri-facebook-fill mr-2"></i>Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank"
                    class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                    <i class="ri-twitter-fill mr-2"></i>Twitter
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank"
                    class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors">
                    <i class="ri-linkedin-fill mr-2"></i>LinkedIn
                </a>
                <button onclick="navigator.clipboard.writeText('{{ url()->current() }}')" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="ri-link mr-2"></i>Copier le lien
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between items-center">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition-colors shadow-lg">
                <i class="ri-arrow-left-line mr-2"></i>
                Retour au blog
            </a>
            @if(isset($nextArticle))
            <a href="{{ route('blog.show', $nextArticle->slug) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                Article suivant
                <i class="ri-arrow-right-line ml-2"></i>
            </a>
            @endif
        </div>
        @else
        <!-- Article Not Found -->
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <i class="ri-file-damage-line text-gray-300 text-9xl mb-6"></i>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Article non trouvé</h2>
            <p class="text-gray-600 mb-8">L'article que vous recherchez n'existe pas ou a été supprimé.</p>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ri-arrow-left-line mr-2"></i>
                Retour au blog
            </a>
        </div>
        @endif
    </div>
</div>
    @include('layouts.footer')
@endsection
