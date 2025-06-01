<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hackathon; // Import the Hackathon model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // To get the authenticated admin user
use Illuminate\Support\Str; // For generating slugs
use Illuminate\Validation\Rule;

class HackathonController extends Controller
{
    /**
     * Display a listing of the hackathons for the admin.
     */
    public function index(): View // Ensure View is imported: use Illuminate\View\View;
    {
        // Fetch all hackathons, ordered by newest first, for example
        $hackathons = Hackathon::orderBy('created_at', 'desc')->paginate(10); // Paginate for longer lists

        return view('admin.hackathons.index', compact('hackathons'));
    }

    /**
     * Show the form for creating a new hackathon.
     */
    public function create(): View // Type-hint the return type
    {
        // This method just needs to return the view that contains the form
        return view('admin.hackathons.create');
    }

     /**
     * Store a newly created hackathon in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:hackathons,name',
            // 'slug' => 'nullable|string|max:255|unique:hackathons,slug', // We'll auto-generate slug
            'description' => 'required|string|min:20',
            'rules' => 'nullable|string',
            'prizes' => 'nullable|string',
            'start_datetime' => 'required|date|after_or_equal:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'registration_opens_at' => 'required|date|before:start_datetime',
            'registration_closes_at' => 'required|date|after:registration_opens_at|before:end_datetime',
            'banner_image_url' => 'nullable|url|max:2048',
        ]);

        // 2. Prepare data for creation (including auto-generated slug and admin_id)
        $hackathonData = $validatedData; // Start with validated data
        $hackathonData['admin_id'] = Auth::id(); // Set the creator as the currently logged-in admin
        $hackathonData['slug'] = Str::slug($validatedData['name']); // Auto-generate slug from the name

        // Ensure unique slug (if a hackathon with the same name but different case exists, slug might collide)
        $originalSlug = $hackathonData['slug'];
        $count = 1;
        while (Hackathon::where('slug', $hackathonData['slug'])->exists()) {
            $hackathonData['slug'] = $originalSlug . '-' . $count++;
        }

        // Default status (optional, as migration already has a default)
        // $hackathonData['status'] = 'upcoming'; // Or determine based on dates

        // 3. Create and save the Hackathon
        $hackathon = Hackathon::create($hackathonData);

        // 4. Redirect with a success message
        // For now, redirect back to the create form with a success message.
        // Later, we can redirect to an admin index page for hackathons.
        return redirect()->route('admin.hackathons.create')
                         ->with('success', 'Hackathon "' . $hackathon->name . '" created successfully!');
    }


    /**
     * Display the specified resource.
     */
     public function show(Hackathon $hackathon): View // Using Route Model Binding
    {
        // The $hackathon variable is automatically injected.
        // You might want to load related data if needed for the admin view, e.g., registrants.
        // $hackathon->load('registrations.user', 'registrations.team', 'projects'); // Example

        return view('admin.hackathons.show', compact('hackathon'));
    }

     public function edit(Hackathon $hackathon): View // Using Route Model Binding
    {
        // The $hackathon variable is automatically injected by Laravel
        // because the route parameter {hackathon} matches the type-hinted variable name.
        return view('admin.hackathons.edit', compact('hackathon'));
    }

    /**
     * Update the specified hackathon in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hackathon  $hackathon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Hackathon $hackathon)
    {
        // 1. Validate the request data
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hackathons')->ignore($hackathon->id), // Ensure name is unique, ignoring the current hackathon
            ],
            'description' => 'required|string|min:20',
            'rules' => 'nullable|string',
            'prizes' => 'nullable|string',
            'start_datetime' => 'required|date', // For update, 'after_or_equal:now' might be too restrictive if editing past events. Adjust if needed.
            'end_datetime' => 'required|date|after:start_datetime',
            'registration_opens_at' => 'required|date|before:start_datetime',
            'registration_closes_at' => 'required|date|after:registration_opens_at|before:end_datetime',
            'banner_image_url' => 'nullable|url|max:2048',
        ]);

        // 2. Prepare data for update
        $hackathonData = $validatedData;

        // If the name changed, regenerate the slug
        if ($hackathon->name !== $validatedData['name']) {
            $hackathonData['slug'] = Str::slug($validatedData['name']);
            // Ensure unique slug
            $originalSlug = $hackathonData['slug'];
            $count = 1;
            // Check for existing slugs, excluding the current hackathon's potential old slug if it was the same
            while (Hackathon::where('slug', $hackathonData['slug'])->where('id', '!=', $hackathon->id)->exists()) {
                $hackathonData['slug'] = $originalSlug . '-' . $count++;
            }
        } else {
            // Keep the existing slug if name hasn't changed
            $hackathonData['slug'] = $hackathon->slug;
        }

        // 3. Update the Hackathon
        $hackathon->update($hackathonData);

        // 4. Redirect with a success message
        return redirect()->route('admin.hackathons.index')
                         ->with('success', 'Hackathon "' . $hackathon->name . '" updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hackathon $hackathon)
    {
        // You might want to add authorization checks here if needed,
        // e.g., can this specific admin delete this hackathon?

        $hackathonName = $hackathon->name; // Get name before deleting for the message
        $hackathon->delete();

        return redirect()->route('admin.hackathons.index')
                         ->with('success', 'Hackathon "' . $hackathonName . '" deleted successfully!');
    }
}
