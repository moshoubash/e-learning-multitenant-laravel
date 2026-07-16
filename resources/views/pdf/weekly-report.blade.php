<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.Weekly Report') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #0A0A0A; padding: 30px; }
        h1 { font-size: 22px; font-weight: bold; text-transform: uppercase; border-bottom: 3px solid #0A0A0A; padding-bottom: 8px; margin-bottom: 20px; }
        h2 { font-size: 16px; font-weight: bold; margin-top: 24px; margin-bottom: 10px; text-transform: uppercase; }
        .period { font-size: 11px; color: #5f5e5e; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #E2E2E2; font-size: 10px; font-weight: bold; text-transform: uppercase; text-align: left; padding: 8px 10px; border: 1px solid #0A0A0A; }
        td { padding: 7px 10px; border: 1px solid #0A0A0A; font-size: 11px; }
        .kpi-grid { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
        .kpi-box { border: 2px solid #0A0A0A; padding: 12px 16px; flex: 1; min-width: 120px; }
        .kpi-label { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #5f5e5e; }
        .kpi-value { font-size: 24px; font-weight: bold; }
        .kpi-sub { font-size: 10px; color: #5f5e5e; }
        .footer { margin-top: 30px; font-size: 10px; color: #5f5e5e; text-align: center; border-top: 2px solid #0A0A0A; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>{{ __('messages.Weekly Analytics Report') }}</h1>
    <p class="period">{{ $period_start }} — {{ $period_end }}</p>

    <div class="kpi-grid">
        <div class="kpi-box">
            <div class="kpi-label">{{ __('messages.Total Users') }}</div>
            <div class="kpi-value">{{ number_format($total_users) }}</div>
            <div class="kpi-sub">+{{ $new_users_week }} {{ __('messages.this week') }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">{{ __('messages.Total Courses') }}</div>
            <div class="kpi-value">{{ number_format($total_courses) }}</div>
            <div class="kpi-sub">+{{ $new_courses_week }} {{ __('messages.this week') }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">{{ __('messages.Total Enrollments') }}</div>
            <div class="kpi-value">{{ number_format($total_enrollments) }}</div>
            <div class="kpi-sub">+{{ $new_enrollments_week }} {{ __('messages.this week') }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">{{ __('messages.Quiz Attempts') }}</div>
            <div class="kpi-value">{{ number_format($total_quiz_attempts) }}</div>
            <div class="kpi-sub">+{{ $quiz_attempts_week }} {{ __('messages.this week') }}</div>
        </div>
    </div>

    @if(count($top_courses) > 0)
        <h2>{{ __('messages.Top Courses') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.Course') }}</th>
                    <th>{{ __('messages.Enrollments') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_courses as $i => $course)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $course['title'] ?? '—' }}</td>
                        <td>{{ $course['enrollments_count'] ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(count($top_students) > 0)
        <h2>{{ __('messages.Top Students') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.Name') }}</th>
                    <th>{{ __('messages.XP Points') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_students as $i => $student)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $student['name'] ?? '—' }}</td>
                        <td>{{ number_format($student['total_points'] ?? 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        {{ __('messages.Generated on') }} {{ $generated_at }}<br>
        {{ config('app.name') }} — {{ __('messages.Automated Report') }}
    </div>
</body>
</html>
