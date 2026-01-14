<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Prism\Prism\ValueObjects\Media\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class GuessImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = $request->input('image');

        if (! $data) {
            abort(400, 'Image not provided');
        }

        [$meta, $content] = explode(',', $data);
        $binary = base64_decode($content);

        $path = storage_path('app/tmp.png');
        file_put_contents($path, $binary);

        $message = new UserMessage(
            "What's in this image?",
            [Image::fromLocalPath(path: $path)]
        );

        $response = Prism::text()
            ->using(Provider::Gemini, 'gemini-3-flash-preview')
            ->withMessages([$message])
            ->asText();

        return $response->text;
    }
}
