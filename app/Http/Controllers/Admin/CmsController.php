<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerOpening;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TimelineEntry;
use App\Support\Activity;
use App\Support\PublicStorage;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function homepage(): View
    {
        return view('admin.cms.homepage', ['company' => SiteData::company(), 'homepage' => SiteData::homepage()]);
    }

    public function updateHomepage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_label' => ['required', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_lines_json' => ['nullable', 'json'],
            'hero_description' => ['required', 'string'],
            'hero_media_path' => ['nullable', 'string', 'max:2048'],
            'feature_image_path' => ['nullable', 'string', 'max:2048'],
            'hero_primary_cta' => ['required', 'string', 'max:255'],
            'hero_secondary_cta' => ['required', 'string', 'max:255'],
            'hero_portal_cta' => ['required', 'string', 'max:255'],
            'featured_label' => ['required', 'string', 'max:255'],
            'featured_title' => ['required', 'string', 'max:255'],
            'featured_description' => ['required', 'string'],
            'featured_cta' => ['required', 'string', 'max:255'],
            'services_label' => ['required', 'string', 'max:255'],
            'services_title' => ['required', 'string', 'max:255'],
            'services_description' => ['required', 'string'],
            'why_label' => ['required', 'string', 'max:255'],
            'why_title' => ['required', 'string', 'max:255'],
            'why_cta' => ['required', 'string', 'max:255'],
            'process_label' => ['required', 'string', 'max:255'],
            'process_title' => ['required', 'string', 'max:255'],
            'process_description' => ['required', 'string'],
            'testimonials_label' => ['required', 'string', 'max:255'],
            'testimonials_title' => ['required', 'string', 'max:255'],
            'insights_label' => ['required', 'string', 'max:255'],
            'insights_title' => ['required', 'string', 'max:255'],
            'insights_description' => ['required', 'string'],
            'portal_title' => ['required', 'string', 'max:255'],
            'portal_description' => ['required', 'string'],
            'portal_cta' => ['required', 'string', 'max:255'],
            'final_cta_title' => ['required', 'string', 'max:255'],
            'final_cta_description' => ['required', 'string'],
            'final_cta_primary' => ['required', 'string', 'max:255'],
            'final_cta_secondary' => ['required', 'string', 'max:255'],
            'why_choose_text' => ['nullable', 'string'],
            'stats_text' => ['nullable', 'string'],
            'process_text' => ['nullable', 'string'],
            'hero_media' => ['nullable', 'file', 'max:204800'],
            'feature_image' => ['nullable', 'file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'hero_description_size' => ['nullable', 'string', 'max:20'],
            'font_sizes' => ['nullable', 'array'],
            'font_sizes.*' => ['string', 'max:20'],
        ]);

        $current = SiteData::homepage();
        $updated = array_replace_recursive($current, [
            'hero_label' => $data['hero_label'],
            'hero_title' => $data['hero_title'] ?? $current['hero_title'] ?? '',
            'hero_lines' => !empty($data['hero_lines_json']) ? json_decode($data['hero_lines_json'], true) : ($current['hero_lines'] ?? []),
            'hero_description' => $data['hero_description'],
            'hero_description_size' => $data['hero_description_size'] ?? ($current['hero_description_size'] ?? 'text-lg'),
            'font_sizes' => array_merge($current['font_sizes'] ?? [], $data['font_sizes'] ?? []),
            'hero_primary_cta' => $data['hero_primary_cta'],
            'hero_secondary_cta' => $data['hero_secondary_cta'],
            'hero_portal_cta' => $data['hero_portal_cta'],
            'featured_section' => [
                'label' => $data['featured_label'],
                'title' => $data['featured_title'],
                'description' => $data['featured_description'],
                'cta' => $data['featured_cta'],
            ],
            'services_section' => [
                'label' => $data['services_label'],
                'title' => $data['services_title'],
                'description' => $data['services_description'],
            ],
            'why_section' => [
                'label' => $data['why_label'],
                'title' => $data['why_title'],
                'cta' => $data['why_cta'],
            ],
            'process_section' => [
                'label' => $data['process_label'],
                'title' => $data['process_title'],
                'description' => $data['process_description'],
            ],
            'testimonials_section' => [
                'label' => $data['testimonials_label'],
                'title' => $data['testimonials_title'],
            ],
            'insights_section' => [
                'label' => $data['insights_label'],
                'title' => $data['insights_title'],
                'description' => $data['insights_description'],
            ],
            'portal_section' => [
                'title' => $data['portal_title'],
                'description' => $data['portal_description'],
                'cta' => $data['portal_cta'],
            ],
            'final_cta' => [
                'title' => $data['final_cta_title'],
                'description' => $data['final_cta_description'],
                'primary_cta' => $data['final_cta_primary'],
                'secondary_cta' => $data['final_cta_secondary'],
            ],
            'why_choose' => $this->lines($data['why_choose_text'] ?? ''),
            'stats' => $this->statPairs($data['stats_text'] ?? ''),
            'process' => $this->processSteps($data['process_text'] ?? ''),
        ]);

        $updated['hero_media_path'] = $this->syncMediaInput(
            $request,
            'hero_media_path',
            'hero_media',
            'cms/homepage',
            $current['hero_media_path'] ?? null,
            'remove_hero_media'
        );
        $updated['feature_image'] = $this->syncMediaInput(
            $request,
            'feature_image_path',
            'feature_image',
            'cms/homepage',
            $current['feature_image'] ?? null,
            'remove_feature_image'
        );

        $this->saveSetting('homepage', $updated);
        Activity::log('cms.homepage.updated', 'Homepage content updated.', $request->user());

        return back()->with('success', 'Homepage settings updated.');
    }

    public function about(): View
    {
        return view('admin.cms.about', [
            'company' => SiteData::company(),
            'about' => SiteData::about(),
            'team' => TeamMember::query()->orderBy('sort_order')->orderBy('name')->get(),
            'timeline' => TimelineEntry::query()->orderBy('sort_order')->orderBy('year')->get(),
        ]);
    }

    public function pages(): View
    {
        return view('admin.cms.pages', ['company' => SiteData::company(), 'pages' => SiteData::publicPages()]);
    }

    public function updatePages(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'services_hero_subtitle' => ['required', 'string', 'max:255'],
            'services_hero_title' => ['required', 'string', 'max:255'],
            'services_hero_description' => ['required', 'string'],
            'services_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'services_hero_media_upload' => ['nullable', 'file', 'max:204800'],
            'services_cta_title' => ['required', 'string', 'max:255'],
            'services_cta_description' => ['required', 'string'],
            'services_cta_button' => ['required', 'string', 'max:255'],

            'projects_hero_subtitle' => ['required', 'string', 'max:255'],
            'projects_hero_title' => ['required', 'string', 'max:255'],
            'projects_hero_description' => ['required', 'string'],
            'projects_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'projects_hero_media_upload' => ['nullable', 'file', 'max:204800'],
            'projects_empty_title' => ['required', 'string', 'max:255'],
            'projects_empty_description' => ['required', 'string'],
            'projects_empty_button' => ['required', 'string', 'max:255'],

            'blog_hero_subtitle' => ['required', 'string', 'max:255'],
            'blog_hero_title' => ['required', 'string', 'max:255'],
            'blog_hero_description' => ['required', 'string'],
            'blog_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'blog_hero_media_upload' => ['nullable', 'file', 'max:204800'],
            'blog_featured_label' => ['required', 'string', 'max:255'],
            'blog_empty_title' => ['required', 'string', 'max:255'],
            'blog_empty_description' => ['required', 'string'],
            'blog_subscribe_title' => ['required', 'string', 'max:255'],
            'blog_subscribe_description' => ['required', 'string'],
            'blog_subscribe_button' => ['required', 'string', 'max:255'],

            'faq_hero_subtitle' => ['required', 'string', 'max:255'],
            'faq_hero_title' => ['required', 'string', 'max:255'],
            'faq_hero_description' => ['required', 'string'],
            'faq_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'faq_hero_media_upload' => ['nullable', 'file', 'max:204800'],
            'faq_empty_title' => ['required', 'string', 'max:255'],
            'faq_empty_description' => ['required', 'string'],

            'careers_hero_subtitle' => ['required', 'string', 'max:255'],
            'careers_hero_title' => ['required', 'string', 'max:255'],
            'careers_hero_description' => ['required', 'string'],
            'careers_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'careers_hero_media_upload' => ['nullable', 'file', 'max:204800'],
            'careers_benefits_text' => ['nullable', 'string'],
            'careers_openings_title' => ['required', 'string', 'max:255'],
            'careers_openings_description' => ['required', 'string'],
            'careers_open_application_kicker' => ['required', 'string', 'max:255'],
            'careers_open_application_title' => ['required', 'string', 'max:255'],
            'careers_open_application_description' => ['required', 'string'],
            'careers_open_application_button' => ['required', 'string', 'max:255'],
            'careers_empty_title' => ['required', 'string', 'max:255'],
            'careers_empty_description' => ['required', 'string'],

            'contact_hero_subtitle' => ['required', 'string', 'max:255'],
            'contact_hero_title' => ['required', 'string', 'max:255'],
            'contact_hero_description' => ['required', 'string'],
            'contact_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'contact_hero_media_upload' => ['nullable', 'file', 'max:204800'],
            'contact_info_title' => ['required', 'string', 'max:255'],
            'contact_quick_access_title' => ['required', 'string', 'max:255'],
            'contact_form_title' => ['required', 'string', 'max:255'],

            'partnerships_hero_subtitle' => ['required', 'string', 'max:255'],
            'partnerships_hero_title' => ['required', 'string', 'max:255'],
            'partnerships_hero_description' => ['required', 'string'],
            'partnerships_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'partnerships_hero_media_upload' => ['nullable', 'file', 'max:204800'],
            'partnerships_reasons_title' => ['required', 'string', 'max:255'],
            'partnerships_reasons_text' => ['nullable', 'string'],
            'partnerships_metrics_text' => ['nullable', 'string'],
            'partnerships_form_title' => ['required', 'string', 'max:255'],
            'partnerships_opportunities_label' => ['required', 'string', 'max:255'],
            'partnerships_opportunities_title' => ['required', 'string', 'max:255'],
            'partnerships_submit_button' => ['required', 'string', 'max:255'],

            'testimonials_hero_subtitle' => ['required', 'string', 'max:255'],
            'testimonials_hero_title' => ['required', 'string', 'max:255'],
            'testimonials_hero_description' => ['required', 'string'],
            'testimonials_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'testimonials_hero_media_upload' => ['nullable', 'file', 'max:204800'],

            'gallery_hero_subtitle' => ['required', 'string', 'max:255'],
            'gallery_hero_title' => ['required', 'string', 'max:255'],
            'gallery_hero_description' => ['required', 'string'],
            'gallery_hero_media_path' => ['nullable', 'string', 'max:2048'],
            'gallery_hero_media_upload' => ['nullable', 'file', 'max:204800'],
            'gallery_empty_title' => ['required', 'string', 'max:255'],
            'gallery_empty_description' => ['required', 'string'],
            'font_sizes' => ['nullable', 'array'],
            'font_sizes.*' => ['string', 'max:20'],
        ]);

        $current = SiteData::publicPages();
        $benefitIcons = collect(data_get($current, 'careers.benefits', []))
            ->pluck('icon')
            ->filter()
            ->values()
            ->all();

        if ($benefitIcons === []) {
            $benefitIcons = ['trending-up', 'shield-check', 'users', 'briefcase'];
        }

        $updated = array_replace_recursive($current, [
            'services' => [
                'hero_subtitle' => $data['services_hero_subtitle'],
                'hero_title' => $data['services_hero_title'],
                'hero_description' => $data['services_hero_description'],
                'cta_title' => $data['services_cta_title'],
                'cta_description' => $data['services_cta_description'],
                'cta_button' => $data['services_cta_button'],
            ],
            'projects' => [
                'hero_subtitle' => $data['projects_hero_subtitle'],
                'hero_title' => $data['projects_hero_title'],
                'hero_description' => $data['projects_hero_description'],
                'empty_title' => $data['projects_empty_title'],
                'empty_description' => $data['projects_empty_description'],
                'empty_button' => $data['projects_empty_button'],
            ],
            'blog' => [
                'hero_subtitle' => $data['blog_hero_subtitle'],
                'hero_title' => $data['blog_hero_title'],
                'hero_description' => $data['blog_hero_description'],
                'featured_label' => $data['blog_featured_label'],
                'empty_title' => $data['blog_empty_title'],
                'empty_description' => $data['blog_empty_description'],
                'subscribe_title' => $data['blog_subscribe_title'],
                'subscribe_description' => $data['blog_subscribe_description'],
                'subscribe_button' => $data['blog_subscribe_button'],
            ],
            'faq' => [
                'hero_subtitle' => $data['faq_hero_subtitle'],
                'hero_title' => $data['faq_hero_title'],
                'hero_description' => $data['faq_hero_description'],
                'empty_title' => $data['faq_empty_title'],
                'empty_description' => $data['faq_empty_description'],
            ],
            'careers' => [
                'hero_subtitle' => $data['careers_hero_subtitle'],
                'hero_title' => $data['careers_hero_title'],
                'hero_description' => $data['careers_hero_description'],
                'benefits' => $this->valueCardsWithIcons($data['careers_benefits_text'] ?? '', $benefitIcons),
                'openings_title' => $data['careers_openings_title'],
                'openings_description' => $data['careers_openings_description'],
                'open_application_kicker' => $data['careers_open_application_kicker'],
                'open_application_title' => $data['careers_open_application_title'],
                'open_application_description' => $data['careers_open_application_description'],
                'open_application_button' => $data['careers_open_application_button'],
                'empty_title' => $data['careers_empty_title'],
                'empty_description' => $data['careers_empty_description'],
            ],
            'contact' => [
                'hero_subtitle' => $data['contact_hero_subtitle'],
                'hero_title' => $data['contact_hero_title'],
                'hero_description' => $data['contact_hero_description'],
                'info_title' => $data['contact_info_title'],
                'quick_access_title' => $data['contact_quick_access_title'],
                'form_title' => $data['contact_form_title'],
            ],
            'partnerships' => [
                'hero_subtitle' => $data['partnerships_hero_subtitle'],
                'hero_title' => $data['partnerships_hero_title'],
                'hero_description' => $data['partnerships_hero_description'],
                'reasons_title' => $data['partnerships_reasons_title'],
                'reasons' => $this->lines($data['partnerships_reasons_text'] ?? ''),
                'metrics' => $this->statPairs($data['partnerships_metrics_text'] ?? ''),
                'form_title' => $data['partnerships_form_title'],
                'opportunities_label' => $data['partnerships_opportunities_label'],
                'opportunities_title' => $data['partnerships_opportunities_title'],
                'submit_button' => $data['partnerships_submit_button'],
            ],
            'testimonials' => [
                'hero_subtitle' => $data['testimonials_hero_subtitle'],
                'hero_title' => $data['testimonials_hero_title'],
                'hero_description' => $data['testimonials_hero_description'],
            ],
            'gallery' => [
                'hero_subtitle' => $data['gallery_hero_subtitle'],
                'hero_title' => $data['gallery_hero_title'],
                'hero_description' => $data['gallery_hero_description'],
                'empty_title' => $data['gallery_empty_title'],
                'empty_description' => $data['gallery_empty_description'],
            ],
        ]);

        $submittedFontSizes = $data['font_sizes'] ?? [];
        foreach (['services', 'projects', 'blog', 'faq', 'careers', 'contact', 'partnerships', 'testimonials', 'gallery'] as $pageKey) {
            $pageFontSizes = [];
            foreach ($submittedFontSizes as $sizeKey => $sizeVal) {
                if (str_starts_with($sizeKey, $pageKey.'_')) {
                    $pageFontSizes[substr($sizeKey, strlen($pageKey) + 1)] = $sizeVal;
                }
            }
            if ($pageFontSizes) {
                $updated[$pageKey]['font_sizes'] = array_merge($updated[$pageKey]['font_sizes'] ?? [], $pageFontSizes);
            }

            $updated[$pageKey]['hero_media_path'] = $this->syncMediaInput(
                $request,
                $pageKey.'_hero_media_path',
                $pageKey.'_hero_media_upload',
                'cms/pages/'.$pageKey,
                data_get($current, $pageKey.'.hero_media_path'),
                'remove_'.$pageKey.'_hero_media'
            );
        }

        $this->saveSetting('public_pages', $updated);
        Activity::log('cms.pages.updated', 'Public page content updated.', $request->user());

        return back()->with('success', 'Public page settings updated.');
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_subtitle' => ['required', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['required', 'string'],
            'story_label' => ['required', 'string', 'max:255'],
            'story_title' => ['required', 'string', 'max:255'],
            'story' => ['required', 'string'],
            'vision_title' => ['required', 'string', 'max:255'],
            'vision' => ['required', 'string'],
            'mission_title' => ['required', 'string', 'max:255'],
            'mission' => ['required', 'string'],
            'values_label' => ['required', 'string', 'max:255'],
            'values_title' => ['required', 'string', 'max:255'],
            'team_label' => ['required', 'string', 'max:255'],
            'team_title' => ['required', 'string', 'max:255'],
            'timeline_label' => ['required', 'string', 'max:255'],
            'timeline_title' => ['required', 'string', 'max:255'],
            'cta_title' => ['required', 'string', 'max:255'],
            'cta_description' => ['required', 'string'],
            'cta_primary' => ['required', 'string', 'max:255'],
            'cta_secondary' => ['required', 'string', 'max:255'],
            'values_text' => ['nullable', 'string'],
            'metrics_text' => ['nullable', 'string'],
            'hero_media_path' => ['nullable', 'string', 'max:2048'],
            'story_image_path' => ['nullable', 'string', 'max:2048'],
            'hero_media' => ['nullable', 'file', 'max:204800'],
            'story_image' => ['nullable', 'file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'font_sizes' => ['nullable', 'array'],
            'font_sizes.*' => ['string', 'max:20'],
        ]);

        $current = SiteData::about();
        $current['font_sizes'] = array_merge($current['font_sizes'] ?? [], $data['font_sizes'] ?? []);
        $current['hero_subtitle'] = $data['hero_subtitle'];
        $current['hero_title'] = $data['hero_title'];
        $current['hero_description'] = $data['hero_description'];
        $current['story_label'] = $data['story_label'];
        $current['story_title'] = $data['story_title'];
        $current['story'] = $this->lines($data['story']);
        $current['vision_title'] = $data['vision_title'];
        $current['vision'] = $data['vision'];
        $current['mission_title'] = $data['mission_title'];
        $current['mission'] = $data['mission'];
        $current['values_label'] = $data['values_label'];
        $current['values_title'] = $data['values_title'];
        $current['values'] = $this->valueCards($data['values_text'] ?? '');
        $current['metrics'] = $this->statPairs($data['metrics_text'] ?? '');
        $current['team_label'] = $data['team_label'];
        $current['team_title'] = $data['team_title'];
        $current['timeline_label'] = $data['timeline_label'];
        $current['timeline_title'] = $data['timeline_title'];
        $current['cta_title'] = $data['cta_title'];
        $current['cta_description'] = $data['cta_description'];
        $current['cta_primary'] = $data['cta_primary'];
        $current['cta_secondary'] = $data['cta_secondary'];
        $current['hero_media_path'] = $this->syncMediaInput(
            $request,
            'hero_media_path',
            'hero_media',
            'cms/about',
            $current['hero_media_path'] ?? null,
            'remove_hero_media'
        );
        $current['story_image'] = $this->syncMediaInput(
            $request,
            'story_image_path',
            'story_image',
            'cms/about',
            $current['story_image'] ?? null,
            'remove_story_image'
        );

        $this->saveSetting('about', $current);
        Activity::log('cms.about.updated', 'About page content updated.', $request->user());

        return back()->with('success', 'About page settings updated.');
    }

    public function storeTeamMember(Request $request): RedirectResponse
    {
        $member = TeamMember::query()->create($this->validatedTeamMember($request));
        Activity::log('cms.team.created', 'About team member created.', $request->user(), $member);

        return back()->with('success', 'Team member added.');
    }

    public function updateTeamMember(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $teamMember->update($this->validatedTeamMember($request, $teamMember));
        Activity::log('cms.team.updated', 'About team member updated.', $request->user(), $teamMember);

        return back()->with('success', 'Team member updated.');
    }

    public function destroyTeamMember(Request $request, TeamMember $teamMember): RedirectResponse
    {
        Activity::log('cms.team.deleted', 'About team member deleted.', $request->user(), $teamMember);
        $this->deleteStoredAsset($teamMember->image_path);
        $teamMember->delete();

        return back()->with('success', 'Team member deleted.');
    }

    public function storeTimelineEntry(Request $request): RedirectResponse
    {
        $entry = TimelineEntry::query()->create($this->validatedTimelineEntry($request));
        Activity::log('cms.timeline.created', 'About timeline entry created.', $request->user(), $entry);

        return back()->with('success', 'Timeline milestone added.');
    }

    public function updateTimelineEntry(Request $request, TimelineEntry $timelineEntry): RedirectResponse
    {
        $timelineEntry->update($this->validatedTimelineEntry($request));
        Activity::log('cms.timeline.updated', 'About timeline entry updated.', $request->user(), $timelineEntry);

        return back()->with('success', 'Timeline milestone updated.');
    }

    public function destroyTimelineEntry(Request $request, TimelineEntry $timelineEntry): RedirectResponse
    {
        Activity::log('cms.timeline.deleted', 'About timeline entry deleted.', $request->user(), $timelineEntry);
        $timelineEntry->delete();

        return back()->with('success', 'Timeline milestone deleted.');
    }

    public function services(): View
    {
        return view('admin.cms.services', ['company' => SiteData::company(), 'services' => Service::query()->orderBy('sort_order')->get()]);
    }

    public function storeService(Request $request): RedirectResponse
    {
        $service = Service::query()->create($this->validatedService($request));
        Activity::log('cms.service.created', 'Service created in CMS.', $request->user(), $service);

        return back()->with('success', 'Service saved.');
    }

    public function updateService(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validatedService($request, $service));
        Activity::log('cms.service.updated', 'Service updated in CMS.', $request->user(), $service);

        return back()->with('success', 'Service updated.');
    }

    public function destroyService(Request $request, Service $service): RedirectResponse
    {
        Activity::log('cms.service.deleted', "Service \"{$service->title}\" deleted.", $request->user(), null);
        $this->deleteStoredAsset($service->image_path);
        $service->delete();

        return redirect()->route('admin.cms.services')->with('success', 'Service deleted.');
    }

    public function projectListings(): View
    {
        return view('admin.cms.projects', [
            'company' => SiteData::company(),
            'projects' => Project::query()->withCount(['assignments', 'units'])->latest()->get(),
        ]);
    }

    public function portfolio(): View
    {
        return view('admin.cms.portfolio', [
            'company' => SiteData::company(),
            'portfolio' => SiteData::portfolio(),
            'projects' => Project::query()->latest()->get(),
        ]);
    }

    public function updateProjectListing(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['ongoing', 'completed', 'upcoming'])],
            'overview' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:2048'],
            'cover_image_upload' => ['nullable', 'file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'launch_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date'],
            'total_units_highlight' => ['nullable', 'string', 'max:255'],
            'available_units_highlight' => ['nullable', 'string', 'max:255'],
            'highlight_labels' => ['nullable', 'array'],
            'highlight_labels.*' => ['nullable', 'string', 'max:255'],
            'highlight_values' => ['nullable', 'array'],
            'highlight_values.*' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = [
            'title' => $data['title'],
            'slug' => ($data['slug'] ?? null) ?: Str::slug($data['title']),
            'location' => $data['location'],
            'category' => $data['category'],
            'overview' => $data['overview'],
            'description' => $data['description'] ?? null,
            'cover_image' => $this->syncMediaInput($request, 'cover_image', 'cover_image_upload', 'cms/projects', $project->cover_image, 'remove_cover_image'),
            'status' => strtolower($data['status']),
            'launch_date' => $data['launch_date'] ?? null,
            'completion_date' => $data['completion_date'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_portfolio' => $request->boolean('is_portfolio'),
            'is_published' => $request->boolean('is_published'),
        ];

        if ($request->has('total_units_highlight') || $request->has('available_units_highlight') || $request->has('highlight_labels') || $request->has('highlight_values')) {
            $payload['highlights'] = $this->projectHighlights(
                $data['highlight_labels'] ?? [],
                $data['highlight_values'] ?? [],
                $data['total_units_highlight'] ?? null,
                $data['available_units_highlight'] ?? null,
            );
        }

        $project->update($payload);

        Activity::log('cms.project.updated', 'Public project listing updated.', $request->user(), $project);

        return back()->with('success', 'Project listing updated.');
    }

    private function projectHighlights(array $labels, array $values, ?string $totalUnits = null, ?string $availableUnits = null): array
    {
        $highlights = [];

        if (filled($totalUnits)) {
            $highlights[] = [
                'label' => 'Total Units',
                'value' => trim($totalUnits),
            ];
        }

        if (filled($availableUnits)) {
            $highlights[] = [
                'label' => 'Available Units',
                'value' => trim($availableUnits),
            ];
        }

        $rows = max(count($labels), count($values));

        for ($index = 0; $index < $rows; $index++) {
            $label = trim((string) ($labels[$index] ?? ''));
            $value = trim((string) ($values[$index] ?? ''));

            if ($label === '' && $value === '') {
                continue;
            }

            $highlights[] = [
                'label' => $label,
                'value' => $value,
            ];
        }

        return $highlights;
    }

    public function updatePortfolio(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_subtitle' => ['required', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['required', 'string'],
            'metrics_text' => ['nullable', 'string'],
            'client_success_label' => ['required', 'string', 'max:255'],
            'client_success_title' => ['required', 'string', 'max:255'],
            'font_sizes' => ['nullable', 'array'],
            'font_sizes.*' => ['string', 'max:20'],
        ]);

        $current = SiteData::portfolio();
        $updated = array_replace_recursive($current, [
            'hero_subtitle' => $data['hero_subtitle'],
            'hero_title' => $data['hero_title'],
            'hero_description' => $data['hero_description'],
            'metrics' => $this->statPairs($data['metrics_text'] ?? ''),
            'client_success_label' => $data['client_success_label'],
            'client_success_title' => $data['client_success_title'],
            'font_sizes' => array_merge($current['font_sizes'] ?? [], $data['font_sizes'] ?? []),
        ]);

        $this->saveSetting('portfolio', $updated);
        Activity::log('cms.portfolio.updated', 'Portfolio page content updated.', $request->user());

        return back()->with('success', 'Portfolio settings updated.');
    }

    public function footer(): View
    {
        return view('admin.cms.footer', [
            'company' => SiteData::company(),
            'footer' => SiteData::footer(),
        ]);
    }

    public function updateFooter(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tagline' => ['nullable', 'string'],
            'newsletter_title' => ['nullable', 'string', 'max:255'],
            'newsletter_description' => ['nullable', 'string'],
            'groups_json' => ['nullable', 'json'],
        ]);

        $current = SiteData::footer();
        $updated = [
            'tagline' => $data['tagline'] ?? ($current['tagline'] ?? ''),
            'newsletter_title' => $data['newsletter_title'] ?? ($current['newsletter_title'] ?? ''),
            'newsletter_description' => $data['newsletter_description'] ?? ($current['newsletter_description'] ?? ''),
            'groups' => !empty($data['groups_json']) ? json_decode($data['groups_json'], true) : ($current['groups'] ?? []),
        ];

        $this->saveSetting('footer', $updated);
        Activity::log('cms.footer.updated', 'Footer content updated.', $request->user());

        return back()->with('success', 'Footer updated.');
    }

    public function blog(): View
    {
        return view('admin.cms.blog', ['company' => SiteData::company(), 'posts' => Post::query()->latest('published_at')->get()]);
    }

    public function storePost(Request $request): RedirectResponse
    {
        $post = Post::query()->create($this->validatedPost($request));
        Activity::log('cms.post.created', 'Blog post created.', $request->user(), $post);

        return back()->with('success', 'Blog post saved.');
    }

    public function updatePost(Request $request, Post $post): RedirectResponse
    {
        $post->update($this->validatedPost($request, $post));
        Activity::log('cms.post.updated', 'Blog post updated.', $request->user(), $post);

        return back()->with('success', 'Blog post updated.');
    }

    public function destroyPost(Request $request, Post $post): RedirectResponse
    {
        Activity::log('cms.post.deleted', "Blog post \"{$post->title}\" deleted.", $request->user(), null);
        $post->delete();

        return redirect()->route('admin.cms.blog')->with('success', 'Blog post deleted.');
    }

    public function testimonials(): View
    {
        return view('admin.cms.testimonials', [
            'company' => SiteData::company(),
            'testimonials' => Testimonial::query()->with('project')->orderBy('sort_order')->get(),
            'projects' => Project::query()->orderBy('title')->get(),
        ]);
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $testimonial = Testimonial::query()->create($this->validatedTestimonial($request));
        Activity::log('cms.testimonial.created', 'Testimonial created.', $request->user(), $testimonial);

        return back()->with('success', 'Testimonial saved.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validatedTestimonial($request, $testimonial));
        Activity::log('cms.testimonial.updated', 'Testimonial updated.', $request->user(), $testimonial);

        return back()->with('success', 'Testimonial updated.');
    }

    public function faq(): View
    {
        return view('admin.cms.faq', ['company' => SiteData::company(), 'faqs' => Faq::query()->orderBy('category')->orderBy('sort_order')->get()]);
    }

    public function storeFaq(Request $request): RedirectResponse
    {
        $faq = Faq::query()->create($this->validatedFaq($request));
        Activity::log('cms.faq.created', 'FAQ item created.', $request->user(), $faq);

        return back()->with('success', 'FAQ item saved.');
    }

    public function updateFaq(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validatedFaq($request));
        Activity::log('cms.faq.updated', 'FAQ item updated.', $request->user(), $faq);

        return back()->with('success', 'FAQ item updated.');
    }

    public function destroyFaq(Request $request, Faq $faq): RedirectResponse
    {
        Activity::log('cms.faq.deleted', "FAQ \"{$faq->question}\" deleted.", $request->user(), null);
        $faq->delete();

        return redirect()->route('admin.cms.faq')->with('success', 'FAQ deleted.');
    }

    public function careers(): View
    {
        return view('admin.cms.careers', ['company' => SiteData::company(), 'roles' => CareerOpening::query()->latest('published_at')->get()]);
    }

    public function adminLogin(): View
    {
        return view('admin.cms.admin-login', [
            'company' => SiteData::company(),
            'loginContent' => SiteData::adminLoginContent(),
        ]);
    }

    public function updateAdminLogin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login_media_path' => ['nullable', 'string', 'max:2048'],
            'login_media_upload' => ['nullable', 'file', 'max:204800'],
            'login_title' => ['required', 'string', 'max:255'],
            'login_description' => ['required', 'string', 'max:500'],
        ]);

        $current = SiteData::adminLoginContent();

        $updated = [
            'title' => $data['login_title'],
            'description' => $data['login_description'],
            'media_path' => $this->syncMediaInput(
                $request,
                'login_media_path',
                'login_media_upload',
                'cms/admin-login',
                $current['media_path'] ?? null,
                'remove_login_media'
            ),
        ];

        $this->saveSetting('admin_login_content', $updated);
        Activity::log('cms.admin-login.updated', 'Admin login page content updated.', $request->user());

        return back()->with('success', 'Admin login page updated.');
    }

    public function portal(): View
    {
        return view('admin.cms.portal', ['company' => SiteData::company(), 'portal' => SiteData::portalContent()]);
    }

    public function updatePortal(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login_media_path' => ['nullable', 'string', 'max:2048'],
            'login_media_upload' => ['nullable', 'file', 'max:204800'],
            'login_marketing_title' => ['required', 'string', 'max:255'],
            'login_marketing_highlight' => ['nullable', 'string', 'max:255'],
            'login_features_text' => ['nullable', 'string'],
            'login_pending_title' => ['required', 'string', 'max:255'],
            'login_pending_description' => ['required', 'string'],
            'login_pending_helper' => ['required', 'string'],
            'login_unrecognized_title' => ['required', 'string', 'max:255'],
            'login_unrecognized_description' => ['required', 'string'],
            'login_unrecognized_button' => ['required', 'string', 'max:255'],
            'login_kyc_title' => ['required', 'string', 'max:255'],
            'login_kyc_description' => ['required', 'string'],
            'login_welcome_title' => ['required', 'string', 'max:255'],
            'login_welcome_description' => ['required', 'string'],
            'login_access_button' => ['required', 'string', 'max:255'],
            'login_request_access_text' => ['required', 'string', 'max:255'],
            'login_request_access_link' => ['required', 'string', 'max:255'],
            'login_support_text' => ['required', 'string', 'max:255'],
            'login_support_link' => ['required', 'string', 'max:255'],

            'dashboard_welcome_title' => ['required', 'string', 'max:255'],
            'dashboard_description' => ['required', 'string'],
            'dashboard_active_projects_label' => ['required', 'string', 'max:255'],
            'dashboard_amount_paid_label' => ['required', 'string', 'max:255'],
            'dashboard_outstanding_label' => ['required', 'string', 'max:255'],
            'dashboard_documents_label' => ['required', 'string', 'max:255'],
            'dashboard_projects_title' => ['required', 'string', 'max:255'],
            'dashboard_projects_link' => ['required', 'string', 'max:255'],
            'dashboard_projects_empty' => ['required', 'string'],
            'dashboard_updates_title' => ['required', 'string', 'max:255'],
            'dashboard_updates_empty' => ['required', 'string'],
            'dashboard_documents_title' => ['required', 'string', 'max:255'],
            'dashboard_documents_empty' => ['required', 'string'],

            'projects_kicker' => ['required', 'string', 'max:255'],
            'projects_title' => ['required', 'string', 'max:255'],
            'projects_description' => ['required', 'string'],
            'projects_filter_button' => ['required', 'string', 'max:255'],
            'projects_assigned_label' => ['required', 'string', 'max:255'],
            'projects_assigned_description' => ['required', 'string'],
            'projects_paid_label' => ['required', 'string', 'max:255'],
            'projects_paid_description' => ['required', 'string'],
            'projects_outstanding_label' => ['required', 'string', 'max:255'],
            'projects_outstanding_description' => ['required', 'string'],
            'projects_empty_kicker' => ['required', 'string', 'max:255'],
            'projects_empty_title' => ['required', 'string', 'max:255'],
            'projects_empty_description' => ['required', 'string'],
            'projects_card_button' => ['required', 'string', 'max:255'],

            'documents_title' => ['required', 'string', 'max:255'],
            'documents_description' => ['required', 'string'],
            'documents_empty_text' => ['required', 'string'],

            'media_kicker' => ['required', 'string', 'max:255'],
            'media_title' => ['required', 'string', 'max:255'],
            'media_description' => ['required', 'string'],
            'media_assets_label' => ['required', 'string', 'max:255'],
            'media_photos_label' => ['required', 'string', 'max:255'],
            'media_videos_label' => ['required', 'string', 'max:255'],
            'media_empty_title' => ['required', 'string', 'max:255'],
            'media_empty_description' => ['required', 'string'],

            'balance_kicker' => ['required', 'string', 'max:255'],
            'balance_title' => ['required', 'string', 'max:255'],
            'balance_description' => ['required', 'string'],
            'balance_contract_label' => ['required', 'string', 'max:255'],
            'balance_paid_label' => ['required', 'string', 'max:255'],
            'balance_outstanding_label' => ['required', 'string', 'max:255'],
            'balance_history_title' => ['required', 'string', 'max:255'],
            'balance_empty_title' => ['required', 'string', 'max:255'],
            'balance_empty_description' => ['required', 'string'],

            'updates_title' => ['required', 'string', 'max:255'],
            'updates_description' => ['required', 'string'],
            'updates_empty_title' => ['required', 'string', 'max:255'],
            'updates_empty_description' => ['required', 'string'],

            'profile_title' => ['required', 'string', 'max:255'],
            'profile_support_title' => ['required', 'string', 'max:255'],
            'profile_support_empty' => ['required', 'string'],
            'profile_update_title' => ['required', 'string', 'max:255'],

            'support_title' => ['required', 'string', 'max:255'],
            'support_description' => ['required', 'string'],
            'support_manager_title' => ['required', 'string', 'max:255'],
            'support_manager_role' => ['required', 'string', 'max:255'],
            'support_phone_label' => ['required', 'string', 'max:255'],
            'support_phone_note' => ['required', 'string', 'max:255'],
            'support_email_label' => ['required', 'string', 'max:255'],
            'support_email_note' => ['required', 'string', 'max:255'],
            'support_hours_label' => ['required', 'string', 'max:255'],
            'support_hours_value' => ['required', 'string', 'max:255'],
            'support_hours_note' => ['required', 'string', 'max:255'],
            'support_message_title' => ['required', 'string', 'max:255'],
            'support_message_button' => ['required', 'string', 'max:255'],
            'support_faq_title' => ['required', 'string', 'max:255'],
            'support_faqs_text' => ['nullable', 'string'],
            'support_tickets_title' => ['required', 'string', 'max:255'],
            'support_tickets_empty' => ['required', 'string'],
        ]);

        $current = SiteData::portalContent();
        $updated = array_replace_recursive($current, [
            'login' => [
                'marketing_title' => $data['login_marketing_title'],
                'marketing_highlight' => $data['login_marketing_highlight'] ?? '',
                'features' => $this->lines($data['login_features_text'] ?? ''),
                'pending_title' => $data['login_pending_title'],
                'pending_description' => $data['login_pending_description'],
                'pending_helper' => $data['login_pending_helper'],
                'unrecognized_title' => $data['login_unrecognized_title'],
                'unrecognized_description' => $data['login_unrecognized_description'],
                'unrecognized_button' => $data['login_unrecognized_button'],
                'kyc_title' => $data['login_kyc_title'],
                'kyc_description' => $data['login_kyc_description'],
                'welcome_title' => $data['login_welcome_title'],
                'welcome_description' => $data['login_welcome_description'],
                'access_button' => $data['login_access_button'],
                'request_access_text' => $data['login_request_access_text'],
                'request_access_link' => $data['login_request_access_link'],
                'support_text' => $data['login_support_text'],
                'support_link' => $data['login_support_link'],
            ],
            'dashboard' => [
                'welcome_title' => $data['dashboard_welcome_title'],
                'description' => $data['dashboard_description'],
                'active_projects_label' => $data['dashboard_active_projects_label'],
                'amount_paid_label' => $data['dashboard_amount_paid_label'],
                'outstanding_label' => $data['dashboard_outstanding_label'],
                'documents_label' => $data['dashboard_documents_label'],
                'projects_title' => $data['dashboard_projects_title'],
                'projects_link' => $data['dashboard_projects_link'],
                'projects_empty' => $data['dashboard_projects_empty'],
                'updates_title' => $data['dashboard_updates_title'],
                'updates_empty' => $data['dashboard_updates_empty'],
                'documents_title' => $data['dashboard_documents_title'],
                'documents_empty' => $data['dashboard_documents_empty'],
            ],
            'projects' => [
                'kicker' => $data['projects_kicker'],
                'title' => $data['projects_title'],
                'description' => $data['projects_description'],
                'filter_button' => $data['projects_filter_button'],
                'assigned_label' => $data['projects_assigned_label'],
                'assigned_description' => $data['projects_assigned_description'],
                'paid_label' => $data['projects_paid_label'],
                'paid_description' => $data['projects_paid_description'],
                'outstanding_label' => $data['projects_outstanding_label'],
                'outstanding_description' => $data['projects_outstanding_description'],
                'empty_kicker' => $data['projects_empty_kicker'],
                'empty_title' => $data['projects_empty_title'],
                'empty_description' => $data['projects_empty_description'],
                'card_button' => $data['projects_card_button'],
            ],
            'documents' => [
                'title' => $data['documents_title'],
                'description' => $data['documents_description'],
                'empty_text' => $data['documents_empty_text'],
            ],
            'media' => [
                'kicker' => $data['media_kicker'],
                'title' => $data['media_title'],
                'description' => $data['media_description'],
                'assets_label' => $data['media_assets_label'],
                'photos_label' => $data['media_photos_label'],
                'videos_label' => $data['media_videos_label'],
                'empty_title' => $data['media_empty_title'],
                'empty_description' => $data['media_empty_description'],
            ],
            'balance' => [
                'kicker' => $data['balance_kicker'],
                'title' => $data['balance_title'],
                'description' => $data['balance_description'],
                'contract_label' => $data['balance_contract_label'],
                'paid_label' => $data['balance_paid_label'],
                'outstanding_label' => $data['balance_outstanding_label'],
                'history_title' => $data['balance_history_title'],
                'empty_title' => $data['balance_empty_title'],
                'empty_description' => $data['balance_empty_description'],
            ],
            'updates' => [
                'title' => $data['updates_title'],
                'description' => $data['updates_description'],
                'empty_title' => $data['updates_empty_title'],
                'empty_description' => $data['updates_empty_description'],
            ],
            'profile' => [
                'title' => $data['profile_title'],
                'support_title' => $data['profile_support_title'],
                'support_empty' => $data['profile_support_empty'],
                'update_title' => $data['profile_update_title'],
            ],
            'support' => [
                'title' => $data['support_title'],
                'description' => $data['support_description'],
                'manager_title' => $data['support_manager_title'],
                'manager_role' => $data['support_manager_role'],
                'phone_label' => $data['support_phone_label'],
                'phone_note' => $data['support_phone_note'],
                'email_label' => $data['support_email_label'],
                'email_note' => $data['support_email_note'],
                'hours_label' => $data['support_hours_label'],
                'hours_value' => $data['support_hours_value'],
                'hours_note' => $data['support_hours_note'],
                'message_title' => $data['support_message_title'],
                'message_button' => $data['support_message_button'],
                'faq_title' => $data['support_faq_title'],
                'faqs' => $this->questionAnswerPairs($data['support_faqs_text'] ?? ''),
                'tickets_title' => $data['support_tickets_title'],
                'tickets_empty' => $data['support_tickets_empty'],
            ],
        ]);

        $updated['login']['media_path'] = $this->syncMediaInput(
            $request,
            'login_media_path',
            'login_media_upload',
            'cms/portal',
            data_get($current, 'login.media_path'),
            'remove_login_media'
        );

        $this->saveSetting('portal_content', $updated);
        Activity::log('cms.portal.updated', 'Portal content updated.', $request->user());

        return back()->with('success', 'Portal content settings updated.');
    }

    public function storeCareer(Request $request): RedirectResponse
    {
        $data = $this->validatedCareer($request);
        $data['hero_image'] = $this->syncMediaInput($request, 'hero_image_path', 'hero_image_upload', 'cms/careers', null, 'remove_hero_image');
        $career = CareerOpening::query()->create($data);
        Activity::log('cms.career.created', 'Career role created.', $request->user(), $career);

        return back()->with('success', 'Career role saved.');
    }

    public function updateCareer(Request $request, CareerOpening $careerOpening): RedirectResponse
    {
        $data = $this->validatedCareer($request, $careerOpening);
        $data['hero_image'] = $this->syncMediaInput($request, 'hero_image_path', 'hero_image_upload', 'cms/careers', $careerOpening->hero_image, 'remove_hero_image');
        $careerOpening->update($data);
        Activity::log('cms.career.updated', 'Career role updated.', $request->user(), $careerOpening);

        return back()->with('success', 'Career role updated.');
    }

    public function destroyCareer(Request $request, CareerOpening $careerOpening): RedirectResponse
    {
        Activity::log('cms.career.deleted', 'Career role deleted.', $request->user(), $careerOpening);
        $careerOpening->delete();

        return back()->with('success', 'Career role deleted.');
    }

    private function saveSetting(string $key, array $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    private function validatedTeamMember(Request $request, ?TeamMember $teamMember = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'image_path' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            'name' => $data['name'],
            'role' => $data['role'],
            'bio' => $data['bio'] ?? null,
            'image_path' => $this->syncMediaInput($request, 'image_path', 'image_upload', 'cms/about/team', $teamMember?->image_path, 'remove_image_path'),
            'sort_order' => $data['sort_order'] ?? ($teamMember?->sort_order ?? 0),
            'is_visible' => $request->boolean('is_visible', true),
        ];
    }

    private function validatedTimelineEntry(Request $request): array
    {
        $data = $request->validate([
            'year' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            'year' => $data['year'],
            'title' => $data['title'],
            'description' => $data['description'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_visible' => $request->boolean('is_visible', true),
        ];
    }

    private function validatedService(Request $request, ?Service $service = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'image_path' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'benefits_text' => ['nullable', 'string'],
            'process_steps_text' => ['nullable', 'string'],
            'related_projects_text' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            'title' => $data['title'],
            'slug' => ($data['slug'] ?? null) ?: Str::slug($data['title']),
            'tagline' => $data['tagline'] ?? null,
            'summary' => $data['summary'],
            'description' => $data['description'] ?? null,
            'image_path' => $this->syncMediaInput($request, 'image_path', 'image_upload', 'cms/services', $service?->image_path, 'remove_image_path'),
            'benefits' => $this->lines($data['benefits_text'] ?? ''),
            'process_steps' => $this->lines($data['process_steps_text'] ?? ''),
            'related_projects' => $this->lines($data['related_projects_text'] ?? ''),
            'sort_order' => $data['sort_order'] ?? ($service?->sort_order ?? 0),
            'is_published' => $request->boolean('is_published', true),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    private function validatedPost(Request $request, ?Post $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'author_name' => ['required', 'string', 'max:255'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'excerpt' => ['required', 'string'],
            'intro' => ['nullable', 'string'],
            'quote' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:2048'],
            'cover_image_upload' => ['nullable', 'file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'content_json' => ['nullable', 'json'],
            'published_at' => ['nullable', 'date'],
        ]);

        return [
            'title' => $data['title'],
            'slug' => ($data['slug'] ?? null) ?: Str::slug($data['title']),
            'category' => $data['category'],
            'author_name' => $data['author_name'],
            'read_time' => $data['read_time'] ?? null,
            'excerpt' => $data['excerpt'],
            'intro' => $data['intro'] ?? null,
            'quote' => $data['quote'] ?? null,
            'cover_image' => $this->syncMediaInput($request, 'cover_image', 'cover_image_upload', 'cms/blog', $post?->cover_image, 'remove_cover_image'),
            'content' => ($data['content_json'] ?? null) ? json_decode($data['content_json'], true) : ($post?->content ?? []),
            'featured' => $request->boolean('featured'),
            'is_published' => $request->boolean('is_published', true),
            'published_at' => $data['published_at'] ?? ($post?->published_at ?? now()),
        ];
    }

    private function validatedTestimonial(Request $request, ?Testimonial $testimonial = null): array
    {
        $data = $request->validate([
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'client_name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image_path' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
        ]);

        return [
            'project_id' => $data['project_id'] ?? null,
            'client_name' => $data['client_name'],
            'role' => $data['role'] ?? null,
            'quote' => $data['quote'],
            'image_path' => $this->syncMediaInput($request, 'image_path', 'image_upload', 'cms/testimonials', $testimonial?->image_path, 'remove_image_path'),
            'rating' => $request->integer('rating', 5),
            'is_visible' => $request->boolean('is_visible', true),
            'sort_order' => $request->integer('sort_order', 0),
        ];
    }

    private function validatedFaq(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]) + [
            'is_published' => $request->boolean('is_published', true),
            'sort_order' => $request->integer('sort_order', 0),
        ];
    }

    private function validatedCareer(Request $request, ?CareerOpening $career = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'requirements_text' => ['nullable', 'string'],
            'benefits_text' => ['nullable', 'string'],
            'hero_image_path' => ['nullable', 'string', 'max:2048'],
            'hero_image_upload' => ['nullable', 'file', 'max:204800'],
        ]);

        return [
            'title' => $data['title'],
            'slug' => ($data['slug'] ?? null) ?: Str::slug($data['title']),
            'department' => $data['department'],
            'location' => $data['location'],
            'employment_type' => $data['employment_type'],
            'summary' => $data['summary'],
            'description' => $data['description'] ?? null,
            'requirements' => $this->lines($data['requirements_text'] ?? ''),
            'benefits' => $this->lines($data['benefits_text'] ?? ''),
            'is_open' => $request->boolean('is_open'),
            'published_at' => $career?->published_at ?? now(),
        ];
    }

    private function lines(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: [])));
    }

    private function statPairs(string $value): array
    {
        return array_values(array_filter(array_map(function (string $line) {
            $parts = array_map('trim', explode('|', $line, 2));

            if (count(array_filter($parts)) < 2) {
                return null;
            }

            return ['label' => $parts[0], 'value' => $parts[1]];
        }, $this->lines($value))));
    }

    private function processSteps(string $value): array
    {
        return array_values(array_filter(array_map(function (string $line) {
            $parts = array_map('trim', explode('|', $line, 3));

            if (count(array_filter($parts)) < 3) {
                return null;
            }

            return ['number' => $parts[0], 'title' => $parts[1], 'description' => $parts[2]];
        }, $this->lines($value))));
    }

    private function valueCards(string $value): array
    {
        return array_values(array_filter(array_map(function (string $line) {
            $parts = array_map('trim', explode('|', $line, 2));

            if (count(array_filter($parts)) < 2) {
                return null;
            }

            return ['title' => $parts[0], 'description' => $parts[1]];
        }, $this->lines($value))));
    }

    private function valueCardsWithIcons(string $value, array $icons): array
    {
        return collect($this->valueCards($value))
            ->values()
            ->map(fn (array $card, int $index) => $card + ['icon' => $icons[$index] ?? 'star'])
            ->all();
    }

    private function questionAnswerPairs(string $value): array
    {
        return array_values(array_filter(array_map(function (string $line) {
            $parts = array_map('trim', explode('|', $line, 2));

            if (count(array_filter($parts)) < 2) {
                return null;
            }

            return ['question' => $parts[0], 'answer' => $parts[1]];
        }, $this->lines($value))));
    }

    private function syncMediaInput(Request $request, string $pathField, string $fileField, string $directory, ?string $currentPath = null, ?string $removeField = null): ?string
    {
        $submittedPath = trim((string) $request->input($pathField, ''));

        if ($request->hasFile($fileField)) {
            $path = PublicStorage::store($request->file($fileField), $directory);
            $this->deleteDifferentStoredAsset($currentPath, $path);

            return $path;
        }

        if ($submittedPath !== '' && $submittedPath !== $currentPath) {
            $this->deleteDifferentStoredAsset($currentPath, $submittedPath);

            return $submittedPath;
        }

        if ($request->boolean($removeField ?: 'remove_'.$pathField)) {
            $this->deleteStoredAsset($currentPath);

            return null;
        }

        if ($submittedPath !== '') {
            return $submittedPath;
        }

        return $currentPath;
    }

    private function deleteDifferentStoredAsset(?string $currentPath, ?string $nextPath): void
    {
        if ($currentPath && $currentPath !== $nextPath) {
            $this->deleteStoredAsset($currentPath);
        }
    }

    private function deleteStoredAsset(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '//')
            || str_starts_with($path, 'data:')
        ) {
            return;
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'images/') || str_starts_with($normalized, 'storage/')) {
            return;
        }

        PublicStorage::delete($normalized);
    }
}
