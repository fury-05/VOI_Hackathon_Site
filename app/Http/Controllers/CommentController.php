<?php

namespace App\Http\Controllers;

use App\Models\Project; // Import the Project model
use App\Models\Comment; // Import the Comment model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // To get the authenticated user

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Project $project)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'content' => 'required|string|min:3|max:1000', // Basic validation rules
        ]);

        // Create the comment using the project's comments relationship
        // This automatically sets commentable_id and commentable_type
        $project->comments()->create([
            'user_id' => Auth::id(), // Or $request->user()->id if you prefer
            'content' => $validatedData['content'],
            // 'parent_id' can be added here if you implement replies
        ]);

        // Redirect back to the project page with a success message
        return redirect()->route('projects.show-public', $project->id)
                         ->with('success', 'Comment posted successfully!');
    }
}
