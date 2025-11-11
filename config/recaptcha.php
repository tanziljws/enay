<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | Set the site key and secret key for Google reCAPTCHA v2
    | Get your keys from: https://www.google.com/recaptcha/admin
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    
    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Version
    |--------------------------------------------------------------------------
    |
    | Version of reCAPTCHA to use (v2 or v3)
    |
    */
    
    'version' => env('RECAPTCHA_VERSION', 'v2'),
    
    /*
    |--------------------------------------------------------------------------
    | Skip CAPTCHA in Local Environment
    |--------------------------------------------------------------------------
    |
    | Skip CAPTCHA validation when in local environment for easier testing
    |
    */
    
    'skip_local' => env('RECAPTCHA_SKIP_LOCAL', true),
];
