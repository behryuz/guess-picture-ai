<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Media\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ai', function () {
    $response = (new Prism())->text()
        ->using(Provider::OpenAI, 'gpt-4o')
        ->withPrompt('Explain the plot of The Matrix in 20 words or less.')
        ->asText();

    $this->info('Response: ' . $response->text);
});

Artisan::command('ai:test', function () {
    $response = (new Prism())
        ->text()
        ->using(Provider::Gemini, 'gemini-3-flash-preview')
        ->withPrompt('Say OK')
        ->asText();

    dd($response);
});

Artisan::command('ai2', function () {
    $message = new UserMessage(
        "What's in this image?",
        [Image::fromLocalPath(path: base_path('image.png'))]
    );

    $response = (new Prism())
        ->text()
        ->using(Provider::Gemini, 'gemini-3-flash-preview')
        ->withMessages([$message])
        ->asText();

    $this->info('Response: ' . $response->text);
});
