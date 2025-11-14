<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageAdminController extends Controller
{
    /**
     * Display a listing of the pages.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $page = Page::create($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified page.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages')->ignore($page->id),
            ],
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $page->update($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
    
    /**
     * Show the form for editing the home page.
     *
     * @return \Illuminate\View\View
     */
    public function editHome()
    {
        $page = Page::where('slug', 'home')->firstOrNew([
            'slug' => 'home',
            'title' => 'Home',
            'content' => '',
            'is_active' => true,
        ]);
        
        return view('admin.pages.edit-home', compact('page'));
    }
    
    /**
     * Update the home page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateHome(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);
        
        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            array_merge($validated, ['is_active' => true])
        );
        
        return redirect()
            ->route('admin.pages.home.edit')
            ->with('success', 'Home page updated successfully.');
    }
    
    /**
     * Show the form for editing the about page.
     *
     * @return \Illuminate\View\View
     */
    public function editAbout()
    {
        $page = Page::where('slug', 'about')->firstOrNew([
            'slug' => 'about',
            'title' => 'About Us',
            'content' => '',
            'is_active' => true,
        ]);
        
        return view('admin.pages.edit-about', compact('page'));
    }
    
    /**
     * Update the about page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAbout(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);
        
        $page = Page::updateOrCreate(
            ['slug' => 'about'],
            array_merge($validated, ['is_active' => true])
        );
        
        return redirect()
            ->route('admin.pages.about.edit')
            ->with('success', 'About page updated successfully.');
    }
    
    /**
     * Show the form for editing the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function editContact()
    {
        $page = Page::where('slug', 'contact')->firstOrNew([
            'slug' => 'contact',
            'title' => 'Contact Us',
            'content' => '',
            'is_active' => true,
        ]);
        
        return view('admin.pages.edit-contact', compact('page'));
    }
    
    /**
     * Update the contact page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);
        
        $page = Page::updateOrCreate(
            ['slug' => 'contact'],
            array_merge($validated, ['is_active' => true])
        );
        
        return redirect()
            ->route('admin.pages.contact.edit')
            ->with('success', 'Contact page updated successfully.');
    }
}
