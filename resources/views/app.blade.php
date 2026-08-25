<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta name="description" content="Open Game Art Community Platform">
        <meta name="theme-color" content="#000000">
        <title>OGA (Not Official)</title>
        <link rel="icon" type="image/png" href="{{ env('VITE_APP_URL') }}/images/icon.png" />
        <link rel="manifest" href="/manifest.webmanifest">
        <link rel="apple-touch-icon" href="{{ env('VITE_APP_URL') }}/images/icon.png" />

        @vite(['resources/css/app.css', 'resources/js/app.ts'])

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Anek+Latin:wght@100..800&family=Press+Start+2P&display=swap" rel="stylesheet">

        <script>
            // PWA Installation prompt
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
            });
        </script>
    </head>

    <body class="antialiased bg-dark-001">
        <div id="app"></div>
    </body>
</html>
