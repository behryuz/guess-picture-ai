<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <style>
        :root {
            --bg-start: #0f172a; /* slate-900 */
            --bg-end: #1e293b;   /* slate-800 */
            --card-bg: #0b1220ee;
            --muted: #94a3b8;    /* slate-400 */
            --text: #e2e8f0;     /* slate-200 */
            --accent: #22d3ee;   /* cyan-400 */
            --accent-2: #8b5cf6; /* violet-500 */
            --danger: #ef4444;   /* red-500 */
            --shadow: 0 10px 30px rgba(0,0,0,.35);
            --radius: 16px;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: "Instrument Sans", system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, "Helvetica Neue", Arial, "Apple Color Emoji", "Segoe UI Emoji";
            color: var(--text);
            background: radial-gradient(1200px 600px at 10% -10%, rgba(34,211,238,.15), transparent),
                        radial-gradient(1000px 500px at 100% 0%, rgba(139,92,246,.15), transparent),
                        linear-gradient(160deg, var(--bg-start), var(--bg-end));
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .container {
            width: 100%;
            max-width: 1040px;
            display: grid;
            grid-template-columns: 1fr 520px;
            gap: 28px;
        }

        @media (max-width: 1100px) {
            .container { grid-template-columns: 1fr; max-width: 720px; }
        }

        .card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
        }

        header.card {
            padding: 28px;
        }

        header h1 {
            margin: 0 0 8px 0;
            font-weight: 700;
            letter-spacing: -0.02em;
            font-size: clamp(26px, 4vw, 36px);
        }

        header p { margin: 0; color: var(--muted); }

        .workspace { padding: 18px; display: grid; gap: 16px; }

        .canvas-wrap {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            background: #0b1220;
            border: 1px solid rgba(255,255,255,.06);
        }

        canvas {
            display: block;
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1; /* keep square on small screens */
            image-rendering: crisp-edges;
            cursor: crosshair;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px;
            background: rgba(15,23,42,.6);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 12px;
        }

        .group { display: flex; align-items: center; gap: 12px; }
        .label { font-size: 13px; color: var(--muted); }

        input[type="color"] {
            -webkit-appearance: none;
            appearance: none;
            width: 36px; height: 36px;
            border: none; padding: 0;
            border-radius: 10px;
            background: transparent;
            outline: 2px solid rgba(255,255,255,.08);
        }
        input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type="color"]::-webkit-color-swatch { border: none; border-radius: 8px; }

        input[type="range"] { width: 140px; }
        input[type="range"]::-webkit-slider-thumb { cursor: pointer; }

        .btn {
            --bg: linear-gradient(135deg, var(--accent), var(--accent-2));
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 0;
            padding: 10px 16px;
            font-weight: 600;
            color: #06101a;
            background: var(--bg);
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(34,211,238,.25), 0 4px 14px rgba(139,92,246,.25);
            cursor: pointer;
            transition: transform .08s ease, filter .2s ease;
        }
        .btn:focus-visible { outline: 3px solid rgba(34,211,238,.45); outline-offset: 2px; }
        .btn:hover { filter: brightness(1.05); }
        .btn:active { transform: translateY(1px); }

        .btn-secondary {
            background: transparent;
            color: var(--text);
            border: 1px solid rgba(255,255,255,.14);
            box-shadow: none;
        }
        .btn-danger { background: var(--danger); color: white; box-shadow: 0 6px 18px rgba(239,68,68,.25); }

        footer { margin-top: 8px; text-align: center; color: var(--muted); font-size: 13px; }
        a.link { color: var(--accent); text-decoration: none; }
        a.link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <header class="card">
        <h1>{{ config('app.name', 'Pictionary') }}</h1>
        <p>Sketch your clue and let the AI guess. Use the toolbar to change color and brush size. When you're ready, hit Submit.</p>
    </header>

    <main class="card workspace">
        <form id="form" action="/guess" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="image" id="image">

            <div class="toolbar">
                <div class="group">
                    <span class="label">Color</span>
                    <input id="color" type="color" value="#000000" aria-label="Brush color">
                </div>
                <div class="group">
                    <span class="label">Size</span>
                    <input id="size" type="range" min="1" max="24" step="1" value="5" aria-label="Brush size">
                </div>
                <div class="group" style="margin-left: auto;">
                    <button id="clear" type="button" class="btn btn-secondary" aria-label="Clear canvas">Clear</button>
                    <button type="submit" class="btn" aria-label="Submit drawing">Submit</button>
                </div>
            </div>

            <div class="canvas-wrap">
                <canvas id="sketchpad" width="500" height="500" aria-label="Drawing canvas"></canvas>
            </div>
        </form>
        <footer>
            Pro tip: draw with your mouse or finger. Short strokes look smoother.
        </footer>
    </main>
</div>

<script>
    const canvas = document.getElementById('sketchpad');
    const ctx = canvas.getContext('2d');
    const colorEl = document.getElementById('color');
    const sizeEl = document.getElementById('size');
    const clearBtn = document.getElementById('clear');

    // Init canvas background to white so exported PNG has white background
    function resetCanvasBackground() {
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.restore();
    }

    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = colorEl.value;
    ctx.lineWidth = Number(sizeEl.value);
    resetCanvasBackground();

    let drawing = false;
    let lastX = 0, lastY = 0;

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return { x: clientX - rect.left, y: clientY - rect.top };
    }

    function startDraw(e) {
        drawing = true;
        const { x, y } = getPos(e);
        lastX = x; lastY = y;
    }

    function draw(e) {
        if (!drawing) { return; }
        const { x, y } = getPos(e);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(x, y);
        ctx.stroke();
        lastX = x; lastY = y;
        e.preventDefault();
    }

    function endDraw() {
        drawing = false;
    }

    // Mouse
    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    window.addEventListener('mouseup', endDraw);

    // Touch
    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    window.addEventListener('touchend', endDraw);

    // Controls
    colorEl.addEventListener('input', () => { ctx.strokeStyle = colorEl.value; });
    sizeEl.addEventListener('input', () => { ctx.lineWidth = Number(sizeEl.value); });
    clearBtn.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        resetCanvasBackground();
    });

    // Handle submit -> convert canvas to Data URL (PNG) and place into hidden input
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
</body>
</html>
