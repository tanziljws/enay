<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RecaptchaService;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    protected $recaptcha;

    public function __construct(RecaptchaService $recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if reCAPTCHA is not enabled
        if (!$this->recaptcha->isEnabled()) {
            return $next($request);
        }

        // Get reCAPTCHA response from request
        $recaptchaResponse = $request->input('g-recaptcha-response');

        // Verify reCAPTCHA
        if (!$this->recaptcha->verify($recaptchaResponse)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi CAPTCHA gagal. Silakan coba lagi.'
                ], 422);
            }

            return back()->withErrors([
                'recaptcha' => 'Verifikasi CAPTCHA gagal. Silakan coba lagi.'
            ])->withInput();
        }

        return $next($request);
    }
}
