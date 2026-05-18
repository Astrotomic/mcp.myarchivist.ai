<?php

namespace App\Http\Controllers\WellKnown;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowOpenaiAppsChallengeController
{
    public function __invoke(): Response
    {
        $token = (string) config('services.openai.apps_challenge_token', '');

        if (empty($token)) {
            throw new NotFoundHttpException;
        }

        return response($token, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
