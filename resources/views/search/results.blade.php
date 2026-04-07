@extends('layouts.app')

@section('meta_title', 'Search Results | Boiema Platform')
@section('meta_description', 'Search results for your query on Boiema Platform')

@section('content')
    @include('layouts.navigation')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Search Results</h1>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('search') }}" class="flex gap-4 mb-6">
                <div class="flex-1">
                    <input type="text" name="q" value="{{ $query }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Search for users, companies, submissions, jobs, articles...">
                </div>
                <div>
                    <select name="type" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All</option>
                        <option value="users" {{ $type === 'users' ? 'selected' : '' }}>Users</option>
                        <option value="companies" {{ $type === 'companies' ? 'selected' : '' }}>Companies</option>
                        <option value="submissions" {{ $type === 'submissions' ? 'selected' : '' }}>Submissions</option>
                        <option value="candidates" {{ $type === 'candidates' ? 'selected' : '' }}>Candidates</option>
                        <option value="jobs" {{ $type === 'jobs' ? 'selected' : '' }}>Jobs</option>
                        <option value="blog" {{ $type === 'blog' ? 'selected' : '' }}>Blog</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    <i class="ri-search-line mr-2"></i>Search
                </button>
            </form>
            
            @if($query)
                <div class="text-gray-600">
                    <p>Found <strong>{{ $totalResults }}</strong> results for "<strong>{{ $query }}</strong>"</p>
                </div>
            @endif
        </div>

        @if($query && $totalResults > 0)
            <!-- Search Results -->
            <div class="space-y-6">
                @foreach($results as $result)
                    <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($result['type'] === 'user') bg-blue-100 text-blue-800
                                        @elseif($result['type'] === 'company') bg-green-100 text-green-800
                                        @elseif($result['type'] === 'submission') bg-purple-100 text-purple-800
                                        @elseif($result['type'] === 'candidate') bg-yellow-100 text-yellow-800
                                        @elseif($result['type'] === 'job') bg-indigo-100 text-indigo-800
                                        @elseif($result['type'] === 'blog') bg-pink-100 text-pink-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($result['type']) }}
                                        @if(isset($result['submission_type']))
                                            ({{ ucfirst(str_replace('_', ' ', $result['submission_type'])) }})
                                        @endif
                                    </span>
                                    <span class="ml-2 text-sm text-gray-500">
                                        {{ $result['created_at']->format('M d, Y') }}
                                    </span>
                                </div>
                                
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                    <a href="{{ $result['url'] }}" class="hover:text-blue-600">
                                        {{ $result['title'] }}
                                    </a>
                                </h3>
                                
                                @if(isset($result['description']) && $result['description'])
                                    <p class="text-gray-600 mb-3">
                                        {{ Str::limit($result['description'], 200) }}
                                    </p>
                                @endif
                                
                                <div class="flex items-center text-sm text-gray-500">
                                    <a href="{{ $result['url'] }}" class="text-blue-600 hover:text-blue-800">
                                        View Details <i class="ri-arrow-right-line ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if(isset($pagination) && $pagination['last_page'] > 1)
                <div class="mt-8 flex justify-center">
                    <nav class="flex space-x-2">
                        @if($pagination['current_page'] > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}" 
                               class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Previous
                            </a>
                        @endif
                        
                        @for($i = 1; $i <= $pagination['last_page']; $i++)
                            @if($i == $pagination['current_page'])
                                <span class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                                   class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor
                        
                        @if($pagination['current_page'] < $pagination['last_page'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}" 
                               class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Next
                            </a>
                        @endif
                    </nav>
                </div>
            @endif

        @elseif($query && $totalResults === 0)
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="ri-search-line"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No results found</h3>
                <p class="text-gray-500 mb-6">
                    We couldn't find any results for "<strong>{{ $query }}</strong>". 
                    Try adjusting your search terms or filters.
                </p>
                <div class="space-y-2 text-sm text-gray-500">
                    <p>Try:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Using different keywords</li>
                        <li>Checking your spelling</li>
                        <li>Using more general terms</li>
                        <li>Removing filters</li>
                    </ul>
                </div>
            </div>

        @else
            <!-- Search Instructions -->
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="ri-search-line"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Search the Platform</h3>
                <p class="text-gray-500 mb-6">
                    Enter your search terms above to find users, companies, submissions, jobs, and articles.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-blue-600 text-3xl mb-3">
                            <i class="ri-user-line"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Users & Companies</h4>
                        <p class="text-sm text-gray-600">Search for registered users and company profiles</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-green-600 text-3xl mb-3">
                            <i class="ri-file-text-line"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Submissions</h4>
                        <p class="text-sm text-gray-600">Find business plans, project proposals, and applications</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-purple-600 text-3xl mb-3">
                            <i class="ri-article-line"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Content</h4>
                        <p class="text-sm text-gray-600">Search job listings, blog articles, and resources</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Auto-submit search form on type change
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.querySelector('select[name="type"]');
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
    @include('layouts.footer')
@endsection



























