<!DOCTYPE html>
<html>
<head>
    <title>Learner Progress</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; vertical-align: top; }
        ul { margin: 0; padding-left: 20px; }
        .top-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Learner Progress</h1>

    <form method="GET" class="top-bar">
        <input type="text" name="search" placeholder="Filter by course" value="{{ $search ?? '' }}">
        
        <select name="sort">
            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>A–Z by name</option>
            <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>Highest Avg Progress</option>
            <option value="lowest" {{ $sort === 'lowest' ? 'selected' : '' }}>Lowest Avg Progress</option>
        </select>

        <label>
            <input type="checkbox" name="show_all_courses" value="1" {{ $showAllCourses ? 'checked' : '' }}>
            Show all courses
        </label>

        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Learner</th>
                <th>Courses & Progress</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($learners as $learner)
                <tr>
                    <td>{{ $learner->full_name }}</td>
                    <td>
                        <ul>
                            @foreach ($learner->courses as $course)
                                @if (!$search || $showAllCourses || stripos($course->name, $search) !== false)
                                    <li>
                                        {{ $course->name }} — {{ number_format($course->pivot->progress, 2) }}%
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @empty
                <tr><td colspan="2">No learners match your filters.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
