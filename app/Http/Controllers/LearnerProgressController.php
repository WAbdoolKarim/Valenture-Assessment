<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Learner;

function getSortProgress($learner, $search)
{
    if (!$search) {
        return $learner->courses->avg('pivot.progress') ?? 0;
    }

    // Get progress only for matching courses
    $matchingCourses = $learner->courses->filter(fn($course) =>
        stripos($course->name, $search) !== false);

    return $matchingCourses->avg('pivot.progress') ?? 0;
}

class LearnerProgressController extends Controller
{
    
public function index(Request $request)
{
    $search = $request->input('search');
    $sort = $request->input('sort', 'name'); // default: alphabetical
    $showAllCourses = $request->boolean('show_all_courses');

    // Eager-load all courses (we’ll filter in memory)
    $learners = Learner::with('courses')->get();

    // If search is active, only keep learners enrolled in at least one matching course
    if ($search) {
        $learners = $learners->filter(function ($learner) use ($search) {
            return $learner->courses->contains(fn($course) =>
                stripos($course->name, $search) !== false);
        });
    }

    // Define how to compute progress for sorting
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
