<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404 | GRID LMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('partials.auth-dynamic-design-styles')

    <style>
        body {
            background-color: var(--color-surface-container-low, #f4f4f4);
            background-image:
                linear-gradient(color-mix(in srgb, var(--color-on-surface, #1a1c1c) 6%, transparent) 1px, transparent 1px),
                linear-gradient(90deg, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 6%, transparent) 1px, transparent 1px),
                radial-gradient(circle at 1px 1px, color-mix(in srgb, var(--color-on-surface, #1a1c1c) 15%, transparent) 1px, transparent 1px);
            background-size: 40px 40px, 40px 40px, 40px 40px;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 font-sans antialiased">

    <main class="w-full max-w-md">
        <div class="mb-10 text-center">
            <h1 class="text-4xl italic font-bold tracking-tighter uppercase text-on-surface">
                GRID <span class="px-2 ltr:ml-1 rtl:mr-1 -tracking-[0.02em] not-italic" style="background-color: var(--color-primary-container, #FFD600); border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 4px;">LMS</span>
            </h1>
            <p class="mt-4 text-sm font-medium tracking-wide uppercase opacity-70 text-on-surface">
                Learning Management System
            </p>
        </div>

        <section class="p-8 text-center border-2 neo-radius bg-surface-container-lowest border-on-surface">
            <div class="text-8xl font-bold text-on-surface leading-none mb-4">404</div>
            <p class="text-lg font-medium text-secondary mb-2">Page not found</p>
            <p class="text-sm text-secondary mb-8">The page you are looking for does not exist or has been moved.</p>
            <a href="/"
                class="inline-flex items-center px-6 py-3 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                <i class="fas fa-arrow-left ltr:mr-2 rtl:ml-2"></i>
                Back to Home
            </a>
        </section>
    </main>

</body>
</html>
