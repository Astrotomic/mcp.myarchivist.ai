<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'archivist' => [
        /*
         * Base URL for the MyArchivist REST API.
         */
        'base_url' => env('ARCHIVIST_BASE_URL', 'https://api.myarchivist.ai'),

        /*
         * Main Archivist web app URL (where OAuth endpoints live).
         */
        'app_url' => env('ARCHIVIST_APP_URL', 'https://app.myarchivist.ai'),

        /*
         * Fallback API key used in local (stdio) mode.
         * In web mode the Bearer token from the incoming request is passed through
         * directly to the Archivist API and this value is ignored.
         */
        'api_key' => env('ARCHIVIST_API_KEY'),
    ],

];
