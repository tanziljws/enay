<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    /**
     * Verify reCAPTCHA response
     *
     * @param string $response
     * @return bool
     */
    public function verify($response)
    {
        // Skip verification in local environment if configured
        if (config('recaptcha.skip_local') && app()->environment('local')) {
            return true;
        }

        // If no secret key configured, skip verification
        $secretKey = config('recaptcha.secret_key');
        if (empty($secretKey)) {
            \Log::warning('reCAPTCHA secret key not configured');
            return true; // Allow in development
        }

        // If no response provided, fail
        if (empty($response)) {
            return false;
        }

        try {
            // Verify with Google
            $verifyResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $response,
                'remoteip' => request()->ip()
            ]);

            $result = $verifyResponse->json();

            return isset($result['success']) && $result['success'] === true;
        } catch (\Exception $e) {
            \Log::error('reCAPTCHA verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get site key for frontend
     *
     * @return string
     */
    public function getSiteKey()
    {
        return config('recaptcha.site_key', '');
    }

    /**
     * Check if reCAPTCHA is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return !empty(config('recaptcha.site_key')) && !empty(config('recaptcha.secret_key'));
    }
}
