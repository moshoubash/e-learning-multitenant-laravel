<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.Certificate') }} - {{ $course->title }}</title>
    <style>
        /*
         * FIX: @page size must use physical units (mm/in), NOT pixels.
         * Using px here causes PDF renderers (wkhtmltopdf, DomPDF, Browsershot)
         * to ignore the size directive entirely, which produces a blank overflow page.
         * A4 landscape = 297mm × 210mm  (≈ 1123px × 794px at 96 dpi).
         */
        @page {
            margin: 0;
            padding: 0;
            size: 297mm 210mm landscape;
        }

        /* FIX: html/body must be exactly the page size so nothing overflows */
        html, body {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            overflow: hidden;           /* kills the blank second page */
            background: #ffffff;
            font-family: 'DejaVu Sans', sans-serif;
        }

        p, h1, h2, h3, h4, div, span, td {
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* ─── Certificate shell ─── */
        .cert-container {
            width: 297mm;
            height: 210mm;
            background: #ffffff;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }


        /* ─── Decorative corner accent (bottom-right) ─── */
        .corner-br {
            position: absolute;
            bottom: 0; right: 0;
            width: 180px; height: 180px;
            background: #1a1c1c;
            clip-path: polygon(100% 0, 100% 100%, 0 100%);
            z-index: 0;
        }

        /* ─── Stamp seal (bottom-right) ─── */
        .stamp {
            position: absolute;
            bottom: -55px; right: -55px;
            width: 340px; height: 340px;
            background: #ffd600;
            border: 4px solid #0a0a0a;
            transform: rotate(15deg);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .stamp-inner { transform: rotate(-15deg); text-align: center; margin-top: 50px; }
        .stamp-star  { font-size: 80px; line-height: 1; color: #1a1c1c; display: block; }
        .stamp-word  { font-size: 11px; font-weight: bold; text-transform: uppercase;
                       letter-spacing: 3px; color: #1a1c1c; display: block;}

        /* ─── Outer border inset ─── */
        .inner-border {
            position: absolute;
            top: 14px; left: 14px; right: 14px; bottom: 14px;
            border: 1.5px solid #0a0a0a;
            pointer-events: none;
            opacity: 0.15;
            z-index: 0;
        }

        /* ─── Header ─── */
        .header {
            position: relative;
            z-index: 10;
            padding: 44px 44px 0 44px;
        }

        .header-table { width: 100%; border-collapse: collapse; }
        .header-left  { text-align: left;  vertical-align: top; }
        .header-right { text-align: right; vertical-align: top; }

        .brand-box {
            width: 44px; height: 44px;
            border: 2px solid #0a0a0a;
            background: #ffd600;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            /* line-height: 4px; */
            font-size: 22px;
        }

        .brand-text  { display: inline-block; vertical-align: middle; margin-left: 10px; }
        .brand-title { font-size: 24px; font-weight: bold; text-transform: uppercase;
                       letter-spacing: -1px; margin: 0; color: #1a1c1c; line-height: 1; }
        .brand-sub   { font-size: 9px; font-weight: bold; text-transform: uppercase;
                       letter-spacing: 3px; margin: 3px 0 0 2px; color: #5f5e5e; }

        .cert-id-label { font-size: 9px; font-weight: bold; text-transform: uppercase;
                         letter-spacing: 2px; color: #5f5e5e; margin: 0; }
        .cert-id-value { font-size: 14px; font-weight: bold; margin: 2px 0 0; color: #1a1c1c; }

        /* ─── Main content ─── */
        .main-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 110px 44px 0 44px;
            margin-top: 22px;       /* tightened — was 100px which caused overflow */
        }

        .badge {
            display: inline-block;
            background: #1a1c1c;
            color: #ffffff;
            padding: 5px 26px;
            border: 2px solid #0a0a0a;
            margin-bottom: 10px;
        }

        .badge-text { font-size: 15px; font-weight: bold; text-transform: uppercase;
                      letter-spacing: 3px; margin: 0; }

        .cert-label  { font-size: 11px; text-transform: uppercase; letter-spacing: 3px;
                       color: #5f5e5e; margin: 0 0 10px; }

        /* ─── Student name ─── */
        .name-wrapper { text-align: center; margin: 0 0 14px; }
        .name-inner   { display: inline-block; position: relative; }

        .student-name {
            font-size: 62px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: -2px;
            color: #1a1c1c;
            margin: 0;
            line-height: 1;
        }

        /* Yellow highlight under the name */
        .name-underline { height: 10px; background: #ffd600; margin-top: -4px; }

        /* ─── Course info ─── */
        .course-label { font-size: 11px; text-transform: uppercase; letter-spacing: 3px;
                        color: #5f5e5e; margin: 0 0 8px; }

        .course-title {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            color: #1a1c1c;
            margin: 0 auto 18px;
            max-width: 680px;
            line-height: 1.2;
        }

        .divider { width: 100px; height: 3px; background: #1a1c1c; margin: 0 auto; }

        /* ─── Footer ─── */
        .footer {
            position: absolute;
            bottom: 40px;
            left: 44px;
            right: 44px;
            z-index: 10;
        }

        .footer-row   { width: 100%; display: table; }
        .footer-left  { display: table-cell; text-align: left;  vertical-align: bottom; }
        .footer-center{ display: table-cell; text-align: center; vertical-align: bottom; }
        .footer-right { display: table-cell; text-align: right; vertical-align: bottom; }

        .date-label { font-size: 9px; font-weight: bold; text-transform: uppercase;
                      letter-spacing: 2px; color: #5f5e5e; margin: 0; }
        .date-value { font-size: 14px; font-weight: bold; text-transform: uppercase;
                      color: #1a1c1c; margin: 2px 0 0; }

        /* Verification QR placeholder */
        /* .verify-label { font-size: 9px; font-weight: bold; text-transform: uppercase;
                        letter-spacing: 2px; color: #5f5e5e; margin: 0 0 4px; }
        .verify-box   { width: 150px; height: 50px; border: 2px solid #1a1c1c;
                        background: #f4f4f4; margin: 0 auto;
                        display: flex; align-items: center; justify-content: center; }
        .verify-icon  { font-size: 28px;  } */

        /* Instructor signature area */
        .sig-line  { width: 140px; height: 2px; background: #0a0a0a; float: right; margin-bottom: 4px; }
        .sig-name  { font-size: 15px; font-weight: bold; color: #1a1c1c; margin: 10px 0 0; }
        .sig-role  { font-size: 9px; font-weight: bold; text-transform: uppercase;
                     color: #5f5e5e; margin: 0; }
    </style>
</head>
<body>
<div class="cert-container">

    {{-- Decorative elements --}}
    <div class="corner-tl"></div>
    <div class="corner-br"></div>
    <div class="stamp">
        <div class="stamp-inner">
            <span class="stamp-star">&#10039;</span>
            <span class="stamp-word">Certified</span>
        </div>
    </div>
    <div class="inner-border"></div>
    <div class="side-stripe"></div>

    {{-- Header --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <span class="brand-box">&#127891;</span>
                    <div class="brand-text">
                        <p class="brand-title">Grid LMS</p>
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

    {{-- Main body --}}
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

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-row">

            {{-- Date --}}
            <div class="footer-left">
                <p class="date-label">{{ __('messages.Date of Issue') }}</p>
                <p class="date-value">{{ $completedAt }}</p>
            </div>

            {{-- Verification QR / icon --}}
            {{-- <div class="footer-center">
                <p class="verify-label">{{ __('messages.Verify Online') }}</p>
                <div class="verify-box">
                    <span class="verify-icon">&#9783;</span>
                </div>
            </div> --}}

            {{-- Instructor --}}
            <div class="footer-right">
                <div class="sig-line"></div>
                <p class="sig-name">{{ $instructor->name ?? '—' }}</p>
                <p class="sig-role">{{ __('messages.Instructor') }}</p>
            </div>

        </div>
    </div>

</div>
</body>
</html>
