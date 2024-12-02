<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/rec.js'])
    <style>
        .col-2 {
            position: absolute;
            top: 0;
            left: 40%; /* Centers horizontally */
            max-width: 100%; /* Ensures image scales properly */
            height: auto; /* Maintains aspect ratio */
        }

        .col-3 {
            position: absolute;
            top: 0;
            left: 60%; /* Centers horizontally */
            max-width: 100%; /* Ensures image scales properly */
            height: auto; /* Maintains aspect ratio */
        }

        #container {
            position: relative;
            display: inline-block;
        }

        #shot {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none; /* Allows clicks to pass through */
        }
    </style>
</head>
<body>
<h1>截圖後，點瀏覽器任意地方，然後按<kbd>Ctrl</kbd> + <kbd>v</kbd></h1>
<div id="container">
    <img id="shot" alt="result">
    <canvas id="overlay"></canvas>
</div>
<div id="members" class="col-2">
</div>
<div class="col-3">
    <img id="sample" src="/img/test.jpg" alt="sample">
</div>
</body>
</html>
