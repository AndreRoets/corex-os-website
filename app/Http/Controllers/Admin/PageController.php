<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\Models\PageRedirect;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Pages/Index', [
            'pages' => Page::orderBy('name')->get(),
        ]);
    }

    public function edit(Page $page): Response
    {
        return Inertia::render('Admin/Pages/Edit', [
            'page' => $page,
            'redirects' => $page->redirects()->latest()->get(),
        ]);
    }

    /**
     * Renaming the slug leaves a 301 behind at the old one, so an inbound
     * link or a search result built on it keeps working rather than 404ing
     * the moment an admin tidies up a URL.
     */
    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $data = $request->validated();
        $oldSlug = $page->slug;

        $page->update($data);

        if ($oldSlug !== $page->slug) {
            PageRedirect::updateOrCreate(
                ['old_slug' => $oldSlug, 'page_id' => $page->id],
                ['status_code' => 301],
            );
        }

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('admin_status', 'Page saved.'.($oldSlug !== $page->slug ? ' The old address now redirects here.' : ''));
    }
}
