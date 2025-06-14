<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learner Progress</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto space-y-6">

<!-- Title and Search bar-->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <h1 class="text-3xl font-semibold text-gray-800 mb-4 sm:mb-0">Learner Progress</h1>

    <form method="GET" class="bg-white rounded-2xl p-4 shadow-md flex flex-col sm:flex-row sm:items-center gap-3">
        <input
            type="text"
            name="search"
            placeholder="Filter by course"
            value="{{ $search ?? '' }}"
            class="w-full sm:w-52 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
        >

        <select
            name="sort"
            class="px-3 py-1.5 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
        >
            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>A–Z by name</option>
            <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>Highest Avg Progress</option>
            <option value="lowest" {{ $sort === 'lowest' ? 'selected' : '' }}>Lowest Avg Progress</option>
        </select>

        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
            <input
                type="checkbox"
                name="show_all_courses"
                value="1"
                {{ $showAllCourses ? 'checked' : '' }}
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >
            Show all courses
        </label>

        <button
            type="submit"
            class="px-4 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition"
        >
            Apply
        </button>
    </form>
</div>

        <!-- Learner Table -->
        <div class="bg-white rounded-2xl shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200">
    <tr>
        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Learner</th>
        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Courses & Progress</th>
    </tr>
</thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($learners as $learner)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $learner->full_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($learner->courses as $course)
                                        @if (!$search || $showAllCourses || stripos($course->name, $search) !== false)
                                            <li>
                                                <span class="font-medium">{{ $course->name }}</span>
                                                — {{ number_format($course->pivot->progress, 2) }}%
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">No learners match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
