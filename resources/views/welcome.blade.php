<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    </head>
    <body>
    <form id="form" action="/guess" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="image" id="image">
        <script>
            const form = document.getElementById('form');
            const imageInput = document.getElementById('image');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                canvas.toBlob(function (blob) {
                    const reader = new FileReader();
                    reader.onloadend = function () {
                        imageInput.value = reader.result;
                        form.submit();
                    };
                    reader.readAsDataURL(blob);
                }, 'image/png');
            });
        </script>
        <button type="submit">Submit</button>
    </form>
    <canvas
            id="sketchpad"
            width="500" height="500"
            style="border: 1px solid #000"></canvas>

    <script>
        const canvas = document.getElementById('sketchpad');
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#fff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.lineWidth = 5;

        let drawing = false;

        canvas.addEventListener('mousedown', (e) => {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!drawing) return;
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
        });

        canvas.addEventListener('mouseup', () => {
            drawing = false;
        });

        canvas.addEventListener('mouseleave', () => {
            drawing = false;
        });

    </script>
    </body>
</html>
