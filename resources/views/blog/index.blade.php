@extends('layouts.app')

@section('content')
    @include('layouts.navigation')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Blog I.M System</h1>
                <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto">
                    Actualités, conseils et ressources pour entrepreneurs et investisseurs
                </p>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Categories -->
            <div class="mb-8 flex flex-wrap gap-3">
                <a href="{{ route('blog.index') }}" class="px-6 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition-colors">
                    Tout
                </a>
                <a href="{{ route('blog.index', ['category' => 'entrepreneuriat']) }}" class="px-6 py-2 bg-white text-gray-700 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    Entrepreneuriat
                </a>
                <a href="{{ route('blog.index', ['category' => 'investissement']) }}" class="px-6 py-2 bg-white text-gray-700 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    Investissement
                </a>
                <a href="{{ route('blog.index', ['category' => 'emploi']) }}" class="px-6 py-2 bg-white text-gray-700 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    Emploi
                </a>
                <a href="{{ route('blog.index', ['category' => 'formation']) }}" class="px-6 py-2 bg-white text-gray-700 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    Formation
                </a>
            </div>

            @if(isset($articles) && $articles->count() > 0)
                <!-- Featured Article -->
                @php $featured = $articles->first(); @endphp
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-12">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="h-64 md:h-auto bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <i class="ri-article-line text-white text-9xl opacity-20"></i>
                        </div>
                        <div class="p-8 flex flex-col justify-center">
                            <div class="flex items-center space-x-4 mb-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ $featured->category ?? 'Actualités' }}
                                </span>
                                <span class="text-gray-500 text-sm">
                                    <i class="ri-calendar-line mr-1"></i>
                                    {{ $featured->created_at->format('d M Y') }}
                                </span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $featured->title }}</h2>
                            <p class="text-gray-600 mb-6">
                                {{ Str::limit(strip_tags($featured->content), 150) }}
                            </p>
                            <a href="{{ route('blog.show', $featured->slug) }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                                Lire la suite
                                <i class="ri-arrow-right-line ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles->skip(1) as $article)
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                            <i class="ri-article-line text-white text-6xl opacity-30"></i>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    {{ $article->category ?? 'Article' }}
                                </span>
                                <span class="text-gray-500 text-sm">
                                    <i class="ri-calendar-line mr-1"></i>
                                    {{ $article->created_at->format('d M Y') }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $article->title }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ Str::limit(strip_tags($article->content), 100) }}
                            </p>
                            <a href="{{ route('blog.show', $article->slug) }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                                Lire plus
                                <i class="ri-arrow-right-line ml-2"></i>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <i class="ri-article-line text-gray-300 text-9xl mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucun article disponible</h3>
                    <p class="text-gray-600 mb-8">Les articles seront bientôt publiés. Revenez plus tard !</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="ri-home-line mr-2"></i>
                        Retour à l'accueil
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-blue-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Restez informé</h2>
                <p class="text-xl text-blue-100 mb-8">Abonnez-vous à notre newsletter pour recevoir les derniers articles</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto flex gap-3">
                    @csrf
                    <input type="email" name="email" required placeholder="Votre email" 
                        class="flex-1 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white focus:outline-none">
                    <button type="submit" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                        S'abonner
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
    @include('layouts.footer')
@endsection
