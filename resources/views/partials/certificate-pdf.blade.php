<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.Certificate') }} - {{ $course->title }}</title>
    <style>
        @page { margin: 0; padding: 0; size: 1123px 794px landscape; }
        body { margin: 0; padding: 0; background: #f4f4f4; font-family: 'DejaVu Sans', sans-serif; }
        .cert-container {
            width: 1123px; height: 794px;
            background: #ffffff;
            position: relative;
            overflow: hidden;
            border: 4px solid #0a0a0a;
            box-sizing: border-box;
        }
        .stamp {
            position: absolute;
            bottom: -70px; right: -70px;
            width: 400px; height: 400px;
            background: #ffd600;
            border: 4px solid #0a0a0a;
            transform: rotate(15deg);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 0;
        }
        .stamp-inner { transform: rotate(-15deg); }
        .stamp-icon svg { width: 100px; height: 100px; display: block; }
        .inner-border {
            position: absolute;
            top: 12px; left: 12px; right: 12px; bottom: 12px;
            border: 2px solid #0a0a0a;
            pointer-events: none;
            opacity: 0.2;
            z-index: 0;
        }
        .header { position: relative; z-index: 10; padding: 30px 40px 0; }
        .header-table { width: 100%; }
        .header-left { text-align: left; vertical-align: top; }
        .header-right { text-align: right; vertical-align: top; }
        .brand-box {
            width: 48px; height: 48px;
            border: 2px solid #0a0a0a;
            background: #ffd600;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: 48px;
            font-size: 24px;
            color: #1a1c1c;
        }
        .brand-text { display: inline-block; vertical-align: middle; margin-left: 10px; }
        .brand-title { font-size: 28px; font-weight: bold; text-transform: uppercase; letter-spacing: -1px; margin: 0; color: #1a1c1c; line-height: 1; }
        .brand-sub { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; margin: 3px 0 0; color: #5f5e5e; }
        .cert-id-label { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; color: #5f5e5e; margin: 0; }
        .cert-id-value { font-size: 16px; font-weight: bold; margin: 2px 0 0; color: #1a1c1c; }
        .main-content {
            position: relative; z-index: 10;
            text-align: center;
            padding: 0 40px;
            margin-top: 100px;
        }
        .badge {
            display: inline-block;
            background: #1a1c1c; color: #ffffff;
            padding: 6px 28px;
            border: 2px solid #0a0a0a;
            transform: rotate(-1deg);
            margin-bottom: 20px;
        }
        .badge-text { font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; margin: 0; }
        .cert-label { font-size: 13px; text-transform: uppercase; letter-spacing: 3px; color: #5f5e5e; margin: 0 0 12px; }
        .name-wrapper { display: inline-block; margin: 0 auto 20px; }
        .student-name {
            font-size: 72px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -2px;
            color: #1a1c1c;
            margin: 0;
            line-height: 1;
            display: inline-block;
            border-bottom: 14px solid #ffd600;
        }
        .course-label { font-size: 13px; text-transform: uppercase; letter-spacing: 3px; color: #5f5e5e; margin: 0 0 12px; }
        .course-title { font-size: 34px; font-weight: bold; text-transform: uppercase; letter-spacing: -1px; color: #1a1c1c; margin: 0 0 24px; max-width: 700px; margin-left: auto; margin-right: auto; line-height: 1.2; }
        .divider { width: 80px; height: 3px; background: #1a1c1c; margin: 0 auto; }
        .footer {
            position: absolute;
            bottom: 30px;
            left: 40px;
            right: 40px;
            z-index: 10;
            overflow: hidden;
        }
        .footer-left { float: left; text-align: left; }
        .footer-right { float: right; text-align: right; }
        .date-label { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; color: #5f5e5e; margin: 0; }
        .date-value { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1a1c1c; margin: 3px 0 0; }
        .sig-name { font-family: 'DejaVu Sans', sans-serif; font-size: 18px; color: #1a1c1c; margin-bottom: 6px; height: 30px; }
        .sig-title { font-weight: bold; text-transform: uppercase; font-size: 9px; letter-spacing: 1px; color: #1a1c1c; margin: 0 0 2px; }
        .sig-line { width: 160px; height: 2px; background: #0a0a0a; margin: 0 auto 4px; }
        .sig-sub { font-size: 8px; text-transform: uppercase; color: #5f5e5e; margin: 0; }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="stamp">
            <div class="stamp-inner">
                <div class="stamp-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M256.0,28.0L293.5,67.7L343.3,45.4L362.7,96.4L417.2,94.8L415.6,149.3L466.6,168.7L444.3,218.5L484.0,256.0L444.3,293.5L466.6,343.3L415.6,362.7L417.2,417.2L362.7,415.6L343.3,466.6L293.5,444.3L256.0,484.0L218.5,444.3L168.7,466.6L149.3,415.6L94.8,417.2L96.4,362.7L45.4,343.3L67.7,293.5L28.0,256.0L67.7,218.5L45.4,168.7L96.4,149.3L94.8,94.8L149.3,96.4L168.7,45.4L218.5,67.7ZM126.0,285.8L201.0,365.8L238.9,365.8L406.9,187.8L369.1,152.2L201.1,330.2L199.0,330.2L164.0,250.2Z" fill="#000000"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="inner-border"></div>

        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <div class="brand-box">&#127891;</div>
                        <div class="brand-text">
                            <p class="brand-title">grid lms</p>
                            <p class="brand-sub">{{ __('messages.Neo-Brutalist Learning OS') }}</p>
                        </div>
                    </td>
                    <td class="header-right">
                        <p class="cert-id-label">{{ __('messages.Certificate ID') }}</p>
                        <p class="cert-id-value">{{ $certificateId }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="main-content">
            <div class="badge">
                <p class="badge-text">{{ __('messages.Certificate of Completion') }}</p>
            </div>

            <p class="cert-label">{{ __('messages.This is to certify that') }}</p>

            <div class="name-wrapper">
                <p class="student-name">{{ $user->name }}</p>
            </div>

            <p class="course-label">{{ __('messages.Has successfully completed the course') }}</p>
            <p class="course-title">{{ $course->title }}</p>

            <div class="divider"></div>
        </div>

        <div class="footer">
            <div class="footer-left">
                <p class="date-label">{{ __('messages.Date of Issue') }}</p>
                <p class="date-value">{{ $completedAt }}</p>
            </div>
            <div class="footer-right">
                <div class="sig-name">@if($instructor){{ $instructor->name }}@else &mdash; @endif</div>
                <p class="sig-title">{{ $instructor->name ?? '—' }}</p>
                <div class="sig-line"></div>
                <p class="sig-sub">{{ __('messages.Instructor') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
