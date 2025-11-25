<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CaptchaController extends Controller
{
    /**
     * Handle OPTIONS request for CORS preflight
     */
    public function options()
    {
        return response('', 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN')
            ->header('Access-Control-Max-Age', '3600');
    }

    /**
     * CAPTCHA expiration time in seconds (5 minutes)
     */
    private const CAPTCHA_EXPIRATION = 300; // 5 minutes
    
    /**
     * Generate CAPTCHA (return code for display)
     * 
     * SECURITY FEATURES:
     * - CAPTCHA expires after 5 minutes
     * - Only generate NEW CAPTCHA if expired, refresh=true, or no existing CAPTCHA
     * - CAPTCHA stored in server-side session (not in client cookie)
     * - Session cookie is HTTP-only and secure
     */
    public function generate(Request $request)
    {
        try {
            // Ensure session is started
            if (!$request->hasSession()) {
                Log::warning('CAPTCHA: No session available, starting session');
                try {
                    $request->session()->start();
                } catch (\Exception $sessionStartError) {
                    Log::error('CAPTCHA: Failed to start session', [
                        'error' => $sessionStartError->getMessage()
                    ]);
                }
            }
            
            $session = $request->session();
            $forceRefresh = $request->boolean('refresh', false);
            $currentTime = time();
            
            // Check if CAPTCHA already exists in session and is not expired
            if (!$forceRefresh && $session->has('captcha') && $session->has('captcha_expires_at')) {
                $captchaExpiresAt = $session->get('captcha_expires_at');
                
                // Check if CAPTCHA is still valid (not expired)
                if ($captchaExpiresAt > $currentTime) {
                    $existingCaptcha = $session->get('captcha');
                    $timeRemaining = $captchaExpiresAt - $currentTime;
                    
                    Log::info('CAPTCHA: Returning existing CAPTCHA from session', [
                        'captcha_text' => substr($existingCaptcha, 0, 2) . '***',
                        'session_id' => $session->getId(),
                        'expires_at' => $captchaExpiresAt,
                        'time_remaining' => $timeRemaining . 's',
                        'force_refresh' => false
                    ]);
                    
                    // Return existing CAPTCHA without resetting session
                    $response = response()->json([
                        'captcha' => $existingCaptcha,
                        'chars' => str_split($existingCaptcha),
                        'timestamp' => $currentTime,
                        'expires_at' => $captchaExpiresAt,
                        'expires_in' => $timeRemaining,
                        'cached' => true
                    ], 200);
                    
                    $this->setCorsHeaders($response);
                    $this->setSessionCookie($response, $session);
                    return $response;
                } else {
                    // CAPTCHA expired, clear it
                    Log::info('CAPTCHA: Expired, generating new one', [
                        'expired_at' => $captchaExpiresAt,
                        'current_time' => $currentTime,
                        'session_id' => $session->getId()
                    ]);
                    $session->forget('captcha');
                    $session->forget('captcha_expires_at');
                }
            }
            
            // Generate NEW CAPTCHA (either expired, refresh=true, or no existing CAPTCHA)
            $captchaText = $this->generateRandomString();
            $expiresAt = $currentTime + self::CAPTCHA_EXPIRATION;
            
            // Store in session with expiration time
            try {
                // Clear any existing CAPTCHA first to prevent conflicts
                $session->forget('captcha');
                $session->forget('captcha_expires_at');
                
                // Store new CAPTCHA with expiration
                $session->put('captcha', $captchaText);
                $session->put('captcha_expires_at', $expiresAt);
                
                // Force save session
                $session->save();
                
                // Verify it was saved
                $savedCaptcha = $session->get('captcha');
                $savedExpiresAt = $session->get('captcha_expires_at');
                
                Log::info('CAPTCHA generated and saved', [
                    'captcha_text' => substr($captchaText, 0, 2) . '***',
                    'captcha_length' => strlen($captchaText),
                    'session_id' => $session->getId(),
                    'expires_at' => $expiresAt,
                    'expires_in' => self::CAPTCHA_EXPIRATION . 's',
                    'has_captcha' => $session->has('captcha'),
                    'saved_captcha' => $savedCaptcha ? substr($savedCaptcha, 0, 2) . '***' : 'null',
                    'saved_matches' => $savedCaptcha === $captchaText,
                    'force_refresh' => $forceRefresh
                ]);
            } catch (\Exception $sessionError) {
                Log::error('CAPTCHA Session Error: ' . $sessionError->getMessage(), [
                    'exception' => get_class($sessionError),
                    'trace' => $sessionError->getTraceAsString(),
                    'session_id' => $request->hasSession() ? $request->session()->getId() : 'no-session'
                ]);
                // Continue anyway - CAPTCHA text is still generated
            }
        } catch (\Exception $e) {
            Log::error('CAPTCHA Generation Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            // Generate new CAPTCHA anyway even if there's an error
            $captchaText = $this->generateRandomString();
            $expiresAt = time() + self::CAPTCHA_EXPIRATION;
        }
        
        // Return JSON with captcha text for CSS display
        $response = response()->json([
            'captcha' => $captchaText,
            'chars' => str_split($captchaText),
            'timestamp' => time(),
            'expires_at' => $expiresAt ?? (time() + self::CAPTCHA_EXPIRATION),
            'expires_in' => self::CAPTCHA_EXPIRATION,
            'cached' => false
        ], 200);
        
        $this->setCorsHeaders($response);
        if ($request->hasSession()) {
            $this->setSessionCookie($response, $request->session());
        }
        return $response;
    }
    
    /**
     * Ensure session cookie is properly set
     * Laravel handles session cookies automatically, but we ensure session is saved
     */
    private function setSessionCookie($response, $session)
    {
        // Laravel automatically sets session cookie via StartSession middleware
        // We just need to ensure session is saved
        try {
            $session->save();
        } catch (\Exception $e) {
            Log::warning('Failed to save session cookie', [
                'error' => $e->getMessage(),
                'session_id' => $session->getId()
            ]);
        }
    }
    
    /**
     * Set CORS headers for CAPTCHA responses
     */
    private function setCorsHeaders($response)
    {
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
    }
    
    /**
     * Verify CAPTCHA
     * 
     * SECURITY: Checks expiration time before verification
     */
    public function verify(Request $request)
    {
        try {
            // Ensure session is available
            if (!$request->hasSession()) {
                $request->session()->start();
            }
            
            $session = $request->session();
            $currentTime = time();
            
            // Check if CAPTCHA exists and is not expired
            if (!$session->has('captcha') || !$session->has('captcha_expires_at')) {
                Log::warning('CAPTCHA Verification Failed: Session CAPTCHA is empty', [
                    'session_id' => $session->getId(),
                    'has_captcha' => $session->has('captcha'),
                    'has_expires_at' => $session->has('captcha_expires_at')
                ]);
                
                $errorResponse = response()->json([
                    'success' => false,
                    'message' => 'Kode CAPTCHA tidak ditemukan. Silakan refresh CAPTCHA.'
                ], 422);
                $this->setCorsHeaders($errorResponse);
                return $errorResponse;
            }
            
            // Check if CAPTCHA is expired
            $captchaExpiresAt = $session->get('captcha_expires_at');
            if ($captchaExpiresAt <= $currentTime) {
                Log::warning('CAPTCHA Verification Failed: CAPTCHA expired', [
                    'session_id' => $session->getId(),
                    'expires_at' => $captchaExpiresAt,
                    'current_time' => $currentTime,
                    'expired_seconds_ago' => $currentTime - $captchaExpiresAt
                ]);
                
                // Clear expired CAPTCHA
                $session->forget('captcha');
                $session->forget('captcha_expires_at');
                $session->save();
                
                $errorResponse = response()->json([
                    'success' => false,
                    'message' => 'Kode CAPTCHA sudah kadaluarsa. Silakan refresh CAPTCHA.'
                ], 422);
                $this->setCorsHeaders($errorResponse);
                return $errorResponse;
            }
            
            // TRIM whitespace and convert to uppercase
            $userInput = strtoupper(trim($request->input('captcha', '')));
            $sessionCaptcha = strtoupper(trim($session->get('captcha', '')));
            
            // Detailed logging for debugging
            Log::info('CAPTCHA Verification', [
                'user_input_raw' => $request->input('captcha', ''),
                'user_input_trimmed' => $userInput,
                'user_input_length' => strlen($userInput),
                'session_captcha_trimmed' => substr($sessionCaptcha, 0, 2) . '***',
                'session_captcha_length' => strlen($sessionCaptcha),
                'match' => $userInput === $sessionCaptcha,
                'session_id' => $session->getId(),
                'expires_at' => $captchaExpiresAt,
                'time_remaining' => $captchaExpiresAt - $currentTime . 's'
            ]);
            
            if ($userInput === $sessionCaptcha && !empty($sessionCaptcha)) {
                // Clear captcha from session after successful verification
                $session->forget('captcha');
                $session->forget('captcha_expires_at');
                $session->save();
                
                Log::info('CAPTCHA Verification Success', [
                    'session_id' => $session->getId(),
                    'user_input' => $userInput
                ]);
                
                $response = response()->json(['success' => true]);
                $this->setCorsHeaders($response);
                return $response;
            }
            
            Log::warning('CAPTCHA Verification Failed: Mismatch', [
                'user_input' => $userInput,
                'session_captcha' => substr($sessionCaptcha, 0, 2) . '***',
                'session_id' => $session->getId()
            ]);
            
            $errorResponse = response()->json([
                'success' => false,
                'message' => 'Kode CAPTCHA salah. Silakan coba lagi.'
            ], 422);
            $this->setCorsHeaders($errorResponse);
            return $errorResponse;
        } catch (\Exception $e) {
            Log::error('CAPTCHA Verify Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'session_id' => $request->hasSession() ? $request->session()->getId() : 'no-session'
            ]);
            
            $errorResponse = response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi CAPTCHA'
            ], 500);
            $this->setCorsHeaders($errorResponse);
            return $errorResponse;
        }
    }
    
    /**
     * Generate random string for CAPTCHA
     */
    private function generateRandomString()
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $length = 6;
        $result = '';
        
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $result;
    }
}
