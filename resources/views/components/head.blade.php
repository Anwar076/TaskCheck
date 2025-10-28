<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- PWA Meta Tags -->
<meta name="description" content="Professioneel taakbeheer en teamsamenwerking platform">
<meta name="theme-color" content="#2563eb">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="TaskCheck">
<meta name="msapplication-TileColor" content="#2563eb">
<meta name="msapplication-tap-highlight" content="no">

<!-- PWA Manifest -->
<link rel="manifest" href="/manifest.json">
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="icon" type="image/png" href="{{ asset('icons/icon-32x32.png') }}">

<!-- Apple Touch Icons -->
<link rel="apple-touch-icon" href="/icons/icon-152x152.png">
<link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-192x192.png">

<!-- Favicon -->
<link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icons/icon-16x16.png">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    'sans': ['Poppins', 'ui-sans-serif', 'system-ui'],
                },
                colors: {
                    primary: {
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                    }
                }
            }
        }
    }
</script>
<style>
    /* Animations */
    @keyframes fadeUp { from { opacity:0; transform:translateY(30px);} to {opacity:1; transform:translateY(0);} }
    @keyframes fadeIn { from { opacity:0;} to {opacity:1;} }
    @keyframes float { 0%,100% {transform:translateY(0);} 50% {transform:translateY(-8px);} }

    .fade-up { animation: fadeUp 0.9s ease-out forwards; }
    .fade-in { animation: fadeIn 1s ease-out forwards; }
    .float { animation: float 6s ease-in-out infinite; }

    /* Hover effects */
    .card-hover { transition: all 0.35s ease; }
    .card-hover:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

    .btn-gradient {
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(139,92,246,0.35);
    }
</style>