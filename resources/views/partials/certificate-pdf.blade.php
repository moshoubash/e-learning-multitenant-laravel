<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.Certificate') }} - {{ $course->title }}</title>
    <style>
        @page { margin: 0; padding: 0; size: 1123px 794px landscape; }
        body { margin: 0; padding: 0; background: #f4f4f4; font-family: 'DejaVu Sans', sans-serif; }
        p, h1, h2, h3, h4, div, span, td { font-family: 'DejaVu Sans', sans-serif; }
        .sig-name { font-family: cursive; font-size: 18px; color: #1a1c1c; margin-bottom: 6px; height: 30px; }
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
        .stamp-star { font-size: 100px; line-height: 1; color: #1a1c1c; }
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
        .brand-sub { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; margin: 3px 0 0 3px; color: #5f5e5e; }
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
            margin-bottom: 20px;
        }
        .badge-text { font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; margin: 0; }
        .cert-label { font-size: 13px; text-transform: uppercase; letter-spacing: 3px; color: #5f5e5e; margin: 0 0 12px; }
        .name-wrapper { text-align: center; margin: 0 0 20px; }
        .name-inner { display: inline-block; position: relative; }
        .student-name {
            font-size: 72px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: -2px;
            color: #1a1c1c;
            margin: 0;
            line-height: 1;
            font-family: 'DejaVu Sans', sans-serif;
        }
        .name-underline { height: 14px; background: #ffd600; }
        .course-label { font-size: 13px; text-transform: uppercase; letter-spacing: 3px; color: #5f5e5e; margin: 0 0 12px; }
        .course-title { font-size: 34px; font-weight: bold; text-transform: uppercase; letter-spacing: -1px; color: #1a1c1c; margin: 0 0 24px; max-width: 700px; margin-left: auto; margin-right: auto; line-height: 1.2; }
        .divider { width: 80px; height: 3px; background: #1a1c1c; margin: 0 auto; }
        .footer {
            position: absolute;
            bottom: 30px;
            left: 40px;
            right: 40px;
            z-index: 10;
        }
        .footer-row { width: 100%; display: table; }
        .footer-left { display: table-cell; text-align: left; vertical-align: bottom; }
        .footer-right { display: table-cell; text-align: right; vertical-align: bottom; }
        .date-label { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; color: #5f5e5e; margin: 0; }
        .date-value { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1a1c1c; margin: 3px 0 0; }

        .sig-title { font-weight: bold; text-transform: uppercase; font-size: 15px; letter-spacing: 1px; color: #1a1c1c; margin: 0 0 2px; }
        .sig-line { width: 160px; height: 2px; background: #0a0a0a; margin: 0 auto 4px; }
        .sig-sub { font-size: 8px; text-transform: uppercase; color: #5f5e5e; margin: 0; }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="stamp">
            <div class="stamp-inner">
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
                            <p class="brand-sub">{{ tenant('name') }}</p>
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
                <div class="name-inner">
                    <p class="student-name">{{ $user->name }}</p>
                    <div class="name-underline"></div>
                </div>
            </div>

            <p class="course-label">{{ __('messages.Has successfully completed the course') }}</p>
            <p class="course-title">{{ $course->title }}</p>

            <div class="divider"></div>
        </div>

        <div class="footer">
            <div class="footer-row">
                <div class="footer-left">
                    <p class="date-label">{{ __('messages.Date of Issue') }}</p>
                    <p class="date-value">{{ $completedAt }}</p>
                </div>
                <div class="footer-right">
                    <p class="sig-sub">{{ __('messages.Instructor') }}</p>
                    <p class="sig-title">{{ $instructor->name ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
