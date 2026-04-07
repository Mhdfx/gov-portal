<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BlogArticle;
use SEO;

class BlogController extends Controller
{
    /**
     * Display a listing of blog articles.
     */
    public function index(Request $request)
    {
        SEO::setTitle('Blog | Boiema Platform');
        SEO::setDescription('Read the latest articles and insights from the Boiema Platform.');

        $query = BlogArticle::published()->with('author');

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->inCategory($request->category);
        }

        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        // Sort by featured first, then by published date
        $articles = $query->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get filter options
        $categories = BlogArticle::published()->distinct()->pluck('category')->filter();
        $authors = BlogArticle::published()->with('author')->get()->pluck('author')->unique('id');

        return view('blog.index', compact('articles', 'categories', 'authors'));
    }

    /**
     * Display the specified blog article.
     */
    public function show(BlogArticle $article)
    {
        // Check if article is published
        if (!$article->isPublished()) {
            abort(404);
        }

        SEO::setTitle($article->title . ' | Boiema Blog');
        SEO::setDescription($article->excerpt);

        // Increment view count
        $article->incrementViews();

        // Load related data
        $article->load('author');
        
        // Get related articles
        $relatedArticles = BlogArticle::published()
            ->where('id', '!=', $article->id)
            ->where(function($query) use ($article) {
                $query->where('category', $article->category)
                      ->orWhereJsonContains('tags', $article->tags);
            })
            ->limit(4)
            ->get();

        // Get recent articles
        $recentArticles = BlogArticle::published()
            ->where('id', '!=', $article->id)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('blog.show', compact('article', 'relatedArticles', 'recentArticles'));
    }

    /**
     * Show the form for creating a new blog article.
     */
    public function create()
    {
        SEO::setTitle('Create Article | Boiema Blog');
        SEO::setDescription('Create a new blog article on the Boiema Platform.');

        return view('blog.create');
    }

    /**
     * Store a newly created blog article.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
        ]);

        // Handle featured image upload
        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $featuredImagePath = $request->file('featured_image')->store('blog/images', 'public');
        }

        $article = BlogArticle::create([
            'author_id' => Auth::id(),
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImagePath,
            'tags' => $request->tags,
            'category' => $request->category,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('blog.show', $article)->with('success', 'Article created successfully!');
    }

    /**
     * Show the form for editing the specified blog article.
     */
    public function edit(BlogArticle $article)
    {
        SEO::setTitle('Edit Article | Boiema Blog');
        SEO::setDescription('Edit your blog article on the Boiema Platform.');

        // Check if user owns this article or is admin
        if ($article->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('blog.edit', compact('article'));
    }

    /**
     * Update the specified blog article.
     */
    public function update(Request $request, BlogArticle $article)
    {
        // Check if user owns this article or is admin
        if ($article->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $featuredImagePath = $request->file('featured_image')->store('blog/images', 'public');
        } else {
            $featuredImagePath = $article->featured_image;
        }

        $article->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImagePath,
            'tags' => $request->tags,
            'category' => $request->category,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $request->status === 'published' && !$article->published_at ? now() : $article->published_at,
        ]);

        return redirect()->route('blog.show', $article)->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified blog article.
     */
    public function destroy(BlogArticle $article)
    {
        // Check if user owns this article or is admin
        if ($article->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete featured image if exists
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->route('blog.index')->with('success', 'Article deleted successfully!');
    }

    /**
     * Like an article.
     */
    public function like(BlogArticle $article)
    {
        $article->incrementLikes();

        return response()->json([
            'success' => true,
            'likes_count' => $article->likes_count
        ]);
    }

    /**
     * Get articles by category.
     */
    public function category($category)
    {
        SEO::setTitle(ucfirst($category) . ' Articles | Boiema Blog');
        SEO::setDescription('Browse articles in the ' . $category . ' category.');

        $articles = BlogArticle::published()
            ->inCategory($category)
            ->with('author')
            ->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = BlogArticle::published()->distinct()->pluck('category')->filter();

        return view('blog.category', compact('articles', 'category', 'categories'));
    }

    /**
     * Get articles by author.
     */
    public function author($authorId)
    {
        $author = \App\Models\User::findOrFail($authorId);
        
        SEO::setTitle($author->username . ' Articles | Boiema Blog');
        SEO::setDescription('Browse articles by ' . $author->username . '.');

        $articles = BlogArticle::published()
            ->where('author_id', $authorId)
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blog.author', compact('articles', 'author'));
    }
}