# AI Guesser (Laravel 12)

A tiny Pictionary-style app: draw on the canvas and let AI (Google Gemini via Prism) guess what it is. The UI is a single page with a modern, responsive canvas and an in-page modal for the AI response.

## Requirements
- PHP 8.4+
- Composer
- SQLite (recommended for local dev)

No Node/Vite build is required for local use — the welcome page uses inline CSS.

## Quick Start
1) Clone and install dependencies

- git clone https://github.com/your-org/demo-pictionary.git
- cd demo-pictionary
- composer install

2) Configure environment

- cp .env.example .env  (if .env does not exist)
- php artisan key:generate

3) Database (SQLite)

- Create the database file: touch database/database.sqlite
- Ensure .env has: DB_CONNECTION=sqlite

4) Required tables for this repo's default config

- Generate tables and run migrations:
  - php artisan session:table
  - php artisan cache:table
  - php artisan queue:table
  - php artisan migrate

5) Configure Gemini API key

- Open .env and set:
  - GEMINI_API_KEY=your_google_generative_ai_key
- The provider configuration is in config/prism.php under the gemini section.
- The controller uses the Gemini model "gemini-3-flash-preview" by default (see app/Http/Controllers/GuessImageController.php). You can change the model there if desired.

6) Run the app

- php artisan serve
- Visit http://127.0.0.1:8000

## How it works
- GET / shows the drawing canvas.
- POST /guess takes your canvas PNG (as a data URL), saves a temporary image, sends it to Gemini via Prism, and returns the guess text that is displayed in a modal.

Key files:
- resources/views/welcome.blade.php — UI and canvas logic, modal for results.
- app/Http/Controllers/GuessImageController.php — Sends the image to Gemini using Prism.
- config/prism.php — Provider API keys and endpoints (GEMINI_API_KEY is read here).
- routes/web.php — Routes for / and /guess.

## Environment variables
At minimum, set the following in .env:
- APP_NAME="AI GUESSER"
- APP_URL=http://localhost:8000
- GEMINI_API_KEY=your_google_generative_ai_key

Optional (already present with safe defaults for local dev):
- DB_CONNECTION=sqlite
- SESSION_DRIVER=database
- CACHE_STORE=database
- QUEUE_CONNECTION=database

Never commit real API keys to version control.

## Testing and code style
- Run tests: php artisan test
- Format PHP with Pint: vendor/bin/pint --dirty

## Troubleshooting
- 403 or CSRF issues on POST /guess: ensure the CSRF token is present and you are posting to the same host shown in APP_URL.
- 500 response with Prism/Gemini: verify GEMINI_API_KEY is set and valid.
- Session/cache/queue table errors: re-run the table commands and php artisan migrate.
- If frontend changes do not appear, try a hard refresh (no npm build is required for this app).

## License
MIT
