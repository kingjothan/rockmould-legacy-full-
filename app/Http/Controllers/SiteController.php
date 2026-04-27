<?php

namespace App\Http\Controllers;

use App\Models\CareerOpening;
use App\Models\Faq;
use App\Models\MediaAsset;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TimelineEntry;
use App\Support\SiteData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('site.home', [
            'company' => SiteData::company(),
            'homepage' => SiteData::homepage(),
            'featuredProjects' => Project::query()->published()->where('is_featured', true)->take(3)->get(),
            'services' => Service::query()->where('is_published', true)->orderBy('sort_order')->take(4)->get(),
            'testimonials' => Testimonial::query()->where('is_visible', true)->orderBy('sort_order')->take(3)->get(),
            'posts' => Post::query()->published()->latest('published_at')->take(3)->get(),
        ]);
    }

    public function about(): View
    {
        return view('site.about', [
            'company' => SiteData::company(),
            'about' => SiteData::about(),
            'team' => TeamMember::query()->where('is_visible', true)->orderBy('sort_order')->get(),
            'timeline' => TimelineEntry::query()->where('is_visible', true)->orderBy('sort_order')->get(),
            'metrics' => [
                ['label' => 'Projects', 'value' => '150+'],
                ['label' => 'Years', 'value' => '20+'],
                ['label' => 'Clients', 'value' => '500+'],
                ['label' => 'Professionals', 'value' => '350+'],
            ],
        ]);
    }

    public function services(): View
    {
        $services = Service::query()->where('is_published', true)->orderBy('sort_order')->get();

        return view('site.services', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('services'),
            'services' => $services,
            'projects' => Project::query()->where('is_published', true)->orderByDesc('is_featured')->take(4)->get()->keyBy('slug'),
        ]);
    }

    public function projects(Request $request): View
    {
        $query = Project::query()->published();

        if ($request->filled('type')) {
            $type = str_replace('-', ' ', $request->string('type')->lower()->toString());
            $query->whereRaw('LOWER(category) LIKE ?', ['%'.$type.'%']);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->lower()->toString());
        }

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', '%'.$search.'%')->orWhere('location', 'like', '%'.$search.'%');
            });
        }

        return view('site.projects.index', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('projects'),
            'projects' => $query->orderByDesc('is_featured')->orderBy('title')->get(),
            'filters' => $request->only(['type', 'status', 'q', 'view']),
        ]);
    }

    public function project(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $project->load(['milestones', 'updates', 'testimonials']);
        $supportsGallerySections = MediaAsset::supportsGallerySections();
        $publicMedia = MediaAsset::query()
            ->visibleToPublicWebsite()
            ->where('project_id', $project->id)
            ->libraryOrdered()
            ->get();
        $gallerySections = MediaAsset::gallerySectionOptions();
        $publicMediaSections = collect($gallerySections)
            ->mapWithKeys(fn (string $label, string $key) => [$key => $publicMedia->where('gallery_section', $key)->values()])
            ->filter(fn ($items) => $items->isNotEmpty());

        if (! $supportsGallerySections && $publicMedia->isNotEmpty()) {
            $publicMediaSections = collect([
                MediaAsset::SECTION_GENERAL => $publicMedia->values(),
            ]);
        }

        return view('site.projects.show', [
            'company' => SiteData::company(),
            'project' => $project,
            'publicMedia' => $publicMedia,
            'gallerySections' => $gallerySections,
            'publicMediaSections' => $publicMediaSections,
            'relatedProjects' => Project::query()
                ->whereKeyNot($project->id)
                ->published()
                ->where('category', $project->category)
                ->take(3)
                ->get(),
        ]);
    }

    public function portfolio(): View
    {
        return view('site.portfolio', [
            'company' => SiteData::company(),
            'portfolio' => SiteData::portfolio(),
            'projects' => Project::query()->published()->where('is_portfolio', true)->orderByDesc('completion_date')->get(),
            'testimonials' => Testimonial::query()->where('is_visible', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function blog(Request $request): View
    {
        $query = Post::query()->published();

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', '%'.$search.'%')->orWhere('excerpt', 'like', '%'.$search.'%');
            });
        }

        return view('site.blog.index', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('blog'),
            'featured' => Post::query()->published()->where('featured', true)->latest('published_at')->first(),
            'posts' => $query->latest('published_at')->get(),
        ]);
    }

    public function post(Post $post): View
    {
        abort_unless($post->is_published && ($post->published_at === null || $post->published_at->isPast()), 404);

        return view('site.blog.show', [
            'company' => SiteData::company(),
            'post' => $post,
            'relatedPosts' => Post::query()->published()->whereKeyNot($post->id)->latest('published_at')->take(3)->get(),
        ]);
    }

    public function faq(Request $request): View
    {
        $query = Faq::query()->where('is_published', true);

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('question', 'like', '%'.$search.'%')->orWhere('answer', 'like', '%'.$search.'%');
            });
        }

        return view('site.faq', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('faq'),
            'faqs' => $query->orderBy('category')->orderBy('sort_order')->get()->groupBy('category'),
        ]);
    }

    public function careers(): View
    {
        return view('site.careers.index', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('careers'),
            'roles' => CareerOpening::query()->published()->latest('published_at')->get(),
        ]);
    }

    public function career(CareerOpening $careerOpening): View
    {
        abort_unless($careerOpening->is_open && ($careerOpening->published_at === null || $careerOpening->published_at->isPast()), 404);

        return view('site.careers.show', [
            'company' => SiteData::company(),
            'role' => $careerOpening,
            'otherRoles' => CareerOpening::query()->published()->whereKeyNot($careerOpening->id)->latest('published_at')->take(3)->get(),
        ]);
    }

    public function contact(): View
    {
        return view('site.contact', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('contact'),
        ]);
    }

    public function partnerships(): View
    {
        return view('site.partnerships', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('partnerships'),
            'projects' => Project::query()->published()->where('is_featured', true)->take(3)->get(),
            'metrics' => SiteData::publicPage('partnerships')['metrics'] ?? [],
        ]);
    }

    public function testimonials(): View
    {
        return view('site.testimonials', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('testimonials'),
            'testimonials' => Testimonial::query()->where('is_visible', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function gallery(Request $request): View
    {
        $query = MediaAsset::query()->visibleToPublicWebsite();
        $supportsGallerySections = MediaAsset::supportsGallerySections();

        if ($request->filled('type')) {
            $query->where('media_type', $request->string('type')->toString());
        }

        if ($request->filled('project')) {
            $query->whereHas('project', fn ($builder) => $builder->where('slug', $request->string('project')->toString()));
        }

        if ($supportsGallerySections && $request->filled('section')) {
            $query->where('gallery_section', $request->string('section')->toString());
        }

        $gallerySections = MediaAsset::gallerySectionOptions();
        $media = $query->with('project')->orderByDesc('is_featured')->orderBy('sort_order')->get();
        $groupedMedia = collect($gallerySections)
            ->mapWithKeys(fn (string $label, string $key) => [$key => $media->where('gallery_section', $key)->values()])
            ->filter(fn ($items) => $items->isNotEmpty());

        if (! $supportsGallerySections && $media->isNotEmpty()) {
            $groupedMedia = collect([
                MediaAsset::SECTION_GENERAL => $media->values(),
            ]);
        }

        return view('site.gallery', [
            'company' => SiteData::company(),
            'page' => SiteData::publicPage('gallery'),
            'media' => $media,
            'groupedMedia' => $groupedMedia,
            'gallerySections' => $gallerySections,
            'supportsGallerySections' => $supportsGallerySections,
            'projects' => Project::query()->published()->orderBy('title')->get(),
        ]);
    }

    public function privacy(): View
    {
        return view('site.privacy', ['company' => SiteData::company()]);
    }

    public function terms(): View
    {
        return view('site.terms', ['company' => SiteData::company()]);
    }

    public function comingSoon(): View
    {
        return view('site.utility', ['company' => SiteData::company(), 'title' => 'Coming Soon', 'message' => 'A new Rockmould experience is being prepared for this section.']);
    }

    public function maintenance(): View
    {
        return view('site.utility', ['company' => SiteData::company(), 'title' => 'Maintenance', 'message' => 'This section is temporarily unavailable while we complete updates.']);
    }
}
