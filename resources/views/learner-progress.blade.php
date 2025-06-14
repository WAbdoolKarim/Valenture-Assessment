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
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight mb-4 sm:mb-0 border-b border-gray-300 pb-2">
            📚 Learner Progress
        </h1>


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
                <option value="name" {{ $sort===
                'name' ? 'selected' : '' }}>A–Z by name</option>
                <option value="highest" {{ $sort===
                'highest' ? 'selected' : '' }}>Highest Avg Progress</option>
                <option value="lowest" {{ $sort===
                'lowest' ? 'selected' : '' }}>Lowest Avg Progress</option>
            </select>

            @if ($search)
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
            @endif


            <div class="flex gap-2 mt-2 sm:mt-0">
                <button
                        type="submit"
                        class="px-4 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition"
                >
                    Apply
                </button>

                <a
                        href="{{ route('learner-progress') }}"
                        class="px-4 py-1.5 bg-gray-300 text-gray-800 text-sm rounded-lg hover:bg-gray-400 transition text-center"
                >
                    Clear
                </a>
                <button
                        type="button"
                        id="toggle-expand-btn"
                        onclick="toggleAllLearners()"
                        class="px-4 py-1.5 text-sm rounded-lg transition text-white bg-green-600 hover:bg-green-700"
                >
                    Expand All
                </button>
            </div>
        </form>
    </div>

    <!-- Learner Table -->
    <div class="bg-white rounded-2xl shadow-md overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700 divide-y divide-gray-200">
            <thead class="bg-gray-300 text-xs uppercase text-gray-800 font-semibold">
            <tr>
                <th class="px-4 py-2 text-left">Learner</th>
                <th class="px-4 py-2 text-left">Avg Progress</th>
                <th class="px-4 py-2 text-left">Courses</th>
                <th class="px-4 py-2"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse ($learners as $learner)
            @php
            $rowId = 'learner-' . $learner->id;
            $avg = number_format($learner->courses->avg('pivot.progress'), 2);
            @endphp
            <tr>
                <td class="px-4 py-3 font-medium">{{ $learner->full_name }}</td>
                <td class="px-4 py-3">{{ $avg }}%</td>
                <td class="px-4 py-3">{{ $learner->courses->count() }}</td>
                <td class="px-4 py-3 text-right">
                    <button
                            onclick="toggleDetails('{{ $rowId }}')"
                            class="text-gray-500 hover:text-blue-600 transition"
                            aria-label="Toggle course list"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>
                </td>
            </tr>
            <tr id="{{ $rowId }}" class="hidden bg-gray-50">
                <td colspan="4" class="px-4 pb-4">
                    <table class="w-full text-sm mt-2 border border-gray-200 rounded-md overflow-hidden">
                        <thead class="bg-gray-100 text-left text-xs text-gray-600 uppercase">
                        <tr>
                            <th class="px-3 py-2">Course</th>
                            <th class="px-3 py-2">Progress</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach ($learner->courses as $course)
                        @if (!$search || $showAllCourses || stripos($course->name, $search) !== false)
                        <tr>
                            <td class="px-3 py-2">{{ $course->name }}</td>
                            <td class="px-3 py-2">{{ number_format($course->pivot->progress, 2) }}%</td>
                        </tr>
                        @endif
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-4 text-center text-gray-500">No learners match your filters.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- JS to handle toggle for full course details -->
    <script>
        let learnersExpanded = false;

        function toggleDetails(id) {
            const row = document.getElementById(id);
            if (row) {
                row.classList.toggle('hidden');
            }
        }

        function toggleAllLearners() {
            learnersExpanded = !learnersExpanded;

            const rows = document.querySelectorAll('[id^="learner-"]');
            rows.forEach(row => {
                if (learnersExpanded) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });

            const button = document.getElementById('toggle-expand-btn');
            if (learnersExpanded) {
                button.textContent = 'Collapse All';
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
            } else {
                button.textContent = 'Expand All';
                button.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
                button.classList.add('bg-green-600', 'hover:bg-green-700');
            }
        }
    </script>
</div>
</body>
</html>
