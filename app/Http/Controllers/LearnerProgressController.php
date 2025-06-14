<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Learner;

function getSortProgress($learner, $search)
{
    if (!$search) {
        return $learner->courses->avg('pivot.progress') ?? 0;
    }

    // Get progress only for matching courses when course search active
    $matchingCourses = $learner->courses->filter(fn($course) =>
        stripos($course->name, $search) !== false);

    return $matchingCourses->avg('pivot.progress') ?? 0;
}

class LearnerProgressController extends Controller
{
    
public function index(Request $request)
{
    $search = $request->input('search');
    $sort = $request->input('sort', 'name');
    $showAllCourses = $request->boolean('show_all_courses');

    $learners = Learner::with('courses')->get();

    // If search is active, only keep learners who are enrolled in at least one matching course
    if ($search) {
        $learners = $learners->filter(function ($learner) use ($search) {
            return $learner->courses->contains(fn($course) =>
                stripos($course->name, $search) !== false);
        });
    }

    // Define sorting requirements
    $learners = match ($sort) {
        'highest' => $learners->sortByDesc(function ($learner) use ($search) {
            return getSortProgress($learner, $search);
        }),
        'lowest' => $learners->sortBy(function ($learner) use ($search) {
            return getSortProgress($learner, $search);
        }),
        default => $learners->sortBy('firstname'),
    };

    return view('learner-progress', [
        'learners' => $learners,
        'search' => $search,
        'sort' => $sort,
        'showAllCourses' => $showAllCourses,
    ]);
}

}
