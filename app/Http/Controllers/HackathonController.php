<?php

namespace App\Http\Controllers;


use App\Models\Hackathon;
use App\Models\HackathonRegistration;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule; // For validation rules

class HackathonController extends Controller
{
    /**
     * Display a listing of public hackathons.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Fetch hackathons that are relevant for public view
        // e.g., upcoming, registration open, or ongoing. Exclude drafts or archived.
        // For now, let's fetch all and order by start date.
        $hackathons = Hackathon::whereIn('status', ['upcoming', 'registration_open', 'ongoing', 'judging', 'completed']) // Example filter
                                ->orderBy('start_datetime', 'asc')
                                ->paginate(9); // Paginate, e.g., 9 hackathons per page

        return view('hackathons.index', compact('hackathons'));
    }





    /**
     * Handle a registration request for the specified hackathon.
     */
    public function register(Request $request, Hackathon $hackathon)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to register.');
        }

        try {
            $now = Carbon::now();
            if (!$hackathon->registration_opens_at || !$hackathon->registration_closes_at || !$hackathon->end_datetime) {
                // Use slug for redirecting
                return redirect()->route('hackathons.public.show', $hackathon->slug)
                                 ->with('error', 'Hackathon registration dates are not properly configured.');
            }

            $regOpens = Carbon::parse($hackathon->registration_opens_at);
            $regCloses = Carbon::parse($hackathon->registration_closes_at);
            $eventEnds = Carbon::parse($hackathon->end_datetime);

        } catch (\Exception $e) {
            // \Log::error('Date parsing error for hackathon registration (Slug: '.$hackathon->slug.'): ' . $e->getMessage());
            // Use slug for redirecting
            return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('error', 'There was an issue processing hackathon dates. Please try again later.');
        }

        $isRegistrationWindowOpen = $now->betweenIncluded($regOpens, $regCloses);
        $isCorrectStatus = $hackathon->status === 'registration_open';
        $isEventNotEnded = $now->lt($eventEnds);

        if (!($isRegistrationWindowOpen && $isCorrectStatus && $isEventNotEnded)) {
            $errorMessage = 'Registration for this hackathon is not currently open.';
            if (!$isRegistrationWindowOpen) $errorMessage = 'The registration window is not currently active.';
            if (!$isCorrectStatus) $errorMessage = 'The hackathon is not currently open for registration according to its status.';
            if (!$isEventNotEnded) $errorMessage = 'This hackathon event has already concluded.';

            // Use slug for redirecting
            return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('error', $errorMessage);
        }

        $existingRegistration = HackathonRegistration::where('hackathon_id', $hackathon->id) // Still use ID for DB query
                                                    ->where('user_id', $user->id)
                                                    ->first();

        if ($existingRegistration) {
            // Use slug for redirecting
            return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('info', 'You are already registered for this hackathon.');
        }

        try {
            HackathonRegistration::create([
                'hackathon_id' => $hackathon->id, // Still use ID for DB foreign key
                'user_id' => $user->id,
                'registered_at' => $now,
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error creating hackathon registration for user ID '.$user->id.' and hackathon ID '.$hackathon->id.': ' . $e->getMessage());
            // Use slug for redirecting
            return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('error', 'Could not process your registration at this time due to a server issue. Please try again later.');
        }

        // Use slug for redirecting
        return redirect()->route('hackathons.public.show', $hackathon->slug)
                         ->with('success', 'You have successfully registered for ' . $hackathon->name . '!');
    }

    /**
     * Display the specified public hackathon.
     */
    public function show(Hackathon $hackathon): View
    {
        $user = Auth::user();
        $isRegistered = false;
        $userProjects = collect(); // Default to an empty collection
        $canSubmitProject = false; // Default to false

        // Ensure $languageColors is initialized; it might be set in your actual controller or passed
        // For this example, I'll ensure it has a default value if not passed from elsewhere.
        // Ideally, you'd consistently pass it from where it's defined (e.g. when we set up ECharts)
        // For now, let's assume it's available or provide a default for the view.
        $languageColors = $this->getLanguageColors(); // Example: call a helper method or define here

        if ($user) {
            $isRegistered = HackathonRegistration::where('hackathon_id', $hackathon->id)
                                                ->where('user_id', $user->id)
                                                ->exists();

            $userProjects = Project::where('user_id', $user->id)
                                   ->whereNull('hackathon_id')
                                   ->orderBy('name', 'asc')
                                   ->get();
        }

        // Date parsing and button logic
        $now = Carbon::now();
        $regOpens = null;
        $regCloses = null;
        $eventStarts = null;
        $eventEnds = null;

        try {
            if ($hackathon->registration_opens_at) $regOpens = Carbon::parse($hackathon->registration_opens_at);
            if ($hackathon->registration_closes_at) $regCloses = Carbon::parse($hackathon->registration_closes_at);
            if ($hackathon->start_datetime) $eventStarts = Carbon::parse($hackathon->start_datetime);
            if ($hackathon->end_datetime) $eventEnds = Carbon::parse($hackathon->end_datetime);
        } catch (\Exception $e) {
            // Log error, dates might be invalid - this hackathon might have issues
            // \Log::error("Error parsing dates for hackathon show page (ID: {$hackathon->id}): " . $e->getMessage());
            // Set dates to null to prevent further errors in comparisons if parsing failed
            $regOpens = $regCloses = $eventStarts = $eventEnds = null;
        }

        // Determine if project submission is currently open
        // Submissions open if status is 'registration_open' or 'ongoing',
        // AND current time is between registration opening and event end.
        if (($hackathon->status === 'registration_open' || $hackathon->status === 'ongoing') && $regOpens && $eventEnds) {
            if ($now->betweenIncluded($regOpens, $eventEnds)) { // Check if within the broader window
                 // Optionally, add a specific submission start if different from regOpens
                 // For example, if submissions only start after regCloses:
                 // if ($now->gt($regCloses) && $now->lt($eventEnds)) {
                 //    $canSubmitProject = true;
                 // }
                 // For now, let's say submission can happen anytime registration is open or event is ongoing, up to event end
                $canSubmitProject = true;
            }
        }


        // --- Registration Button Logic ---
        $registrationButton = [
            'text' => 'View Status', // Default
            'disabled' => true,
            'can_submit_form' => false,
            'class' => 'bg-gray-400 text-white cursor-not-allowed',
        ];

        if ($isRegistered) {
            $registrationButton['text'] = 'Already Registered';
            $registrationButton['class'] = 'bg-blue-500 hover:bg-blue-600 text-white cursor-default';
        } elseif ($regOpens && $regCloses && $eventEnds && $now->betweenIncluded($regOpens, $regCloses) && $hackathon->status === 'registration_open' && $now->lt($eventEnds)) {
            $registrationButton['text'] = 'Register Now!';
            $registrationButton['disabled'] = false;
            $registrationButton['can_submit_form'] = true;
            $registrationButton['class'] = 'bg-green-500 hover:bg-green-600 text-white';
        } elseif ($regOpens && $now->lt($regOpens) && $hackathon->status === 'upcoming') {
            $registrationButton['text'] = 'Registration Opens ' . $regOpens->format('M d');
        } elseif ($regCloses && $eventStarts && $now->gt($regCloses) && $now->lt($eventStarts)) { // After reg closes, before event starts
            $registrationButton['text'] = 'Registration Closed';
        } elseif ($eventStarts && $eventEnds && $now->betweenIncluded($eventStarts, $eventEnds) && $hackathon->status === 'ongoing') {
            // If ongoing, but registration is closed, what to show? Could be "Event Ongoing"
            // The $canSubmitProject logic above handles submission for ongoing.
            // This button logic is primarily for the *registration* action.
            // If user is not registered and registration is closed but event ongoing:
            if (!$isRegistered) {
                $registrationButton['text'] = 'Registration Closed - Event Ongoing';
            }
        } elseif (in_array($hackathon->status, ['judging'])) {
             $registrationButton['text'] = 'Event Judging';
        } elseif (in_array($hackathon->status, ['completed']) || ($eventEnds && $now->gt($eventEnds)) ) {
             $registrationButton['text'] = 'Event Completed';
        }
        // If it falls through and user is not registered, it might still show "View Status" or "Registration Info"

        return view('hackathons.show', compact(
            'hackathon',
            'isRegistered',
            'userProjects',
            'canSubmitProject',
            'registrationButton',
            'languageColors' // Ensure this is passed if your view uses it for ECharts legend
        ));
    }

      // Example helper for language colors if you centralize it here
    private function getLanguageColors(): array
    {
        return [
            'JavaScript' => '#f1e05a', 'JS' => '#f1e05a',
            'PHP' => '#4F5D95', 'HTML' => '#e34c26',
            'CSS' => '#563d7c', 'Python' => '#3572A5',
            'Java' => '#b07219', 'TypeScript' => '#2b7489',
            'Ruby' => '#701516',
            // Add more
            'default' => '#cccccc'
        ];
    }

     public function submitProject(Request $request, Hackathon $hackathon)
    {
        $user = Auth::user();

        // 1. Validate the incoming request
        $validated = $request->validate([
            'project_id' => [
                'required',
                'integer',
                Rule::exists('projects', 'id')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
        ]);

        // 2. Find the user's registration for this hackathon
        $registration = HackathonRegistration::where('hackathon_id', $hackathon->id)
                                           ->where('user_id', $user->id)
                                           ->first();

        if (!$registration) {
            return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('error', 'You are not registered for this hackathon.');
        }

        // 3. Check if a project is already submitted for this registration
        if ($registration->project_id) {
            return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('info', 'You have already submitted a project for this hackathon.');
        }

        // 4. Server-side check for submission window (consistent with show method's $canSubmitProject logic)
        $now = Carbon::now();
        $submissionWindowIsOpen = false;

        if (($hackathon->status === 'registration_open' || $hackathon->status === 'ongoing') &&
            $hackathon->registration_opens_at && $hackathon->end_datetime) {
            try {
                // Assuming submissions open when registration opens and close when the event ends.
                $submissionOpens = Carbon::parse($hackathon->registration_opens_at);
                $submissionCloses = Carbon::parse($hackathon->end_datetime);

                if ($now->betweenIncluded($submissionOpens, $submissionCloses)) {
                    $submissionWindowIsOpen = true;
                }
            } catch (\Exception $e) {
                // \Log::error("Error parsing submission dates for submitProject (Hackathon ID: {$hackathon->id}): " . $e->getMessage());
                $submissionWindowIsOpen = false; // Safety net if dates are unparseable
            }
        }

        if (!$submissionWindowIsOpen) {
            return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('error', 'Project submission for this hackathon is not currently open.');
        }

        // 5. Get the selected project and ensure it has a GitHub repo URL
        $projectToSubmit = Project::find($validated['project_id']);
        if (!$projectToSubmit || empty($projectToSubmit->github_repo_url)) {
            return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('error', 'The selected project must have a GitHub repository URL set. Please update your project details.');
        }

        // 6. Link the project
        $registration->project_id = $projectToSubmit->id;
        $registration->save();

        if (is_null($projectToSubmit->hackathon_id)) {
            $projectToSubmit->hackathon_id = $hackathon->id;
            $projectToSubmit->status = 'submitted'; // Optionally update project status
            $projectToSubmit->save();
        } else if ($projectToSubmit->hackathon_id != $hackathon->id) {
             return redirect()->route('hackathons.public.show', $hackathon->slug)
                             ->with('error', 'This project is already associated with another hackathon.');
        }

        return redirect()->route('hackathons.public.show', $hackathon->slug)
                         ->with('success', 'Project "' . $projectToSubmit->name . '" submitted successfully to ' . $hackathon->name . '!');
    }

}
