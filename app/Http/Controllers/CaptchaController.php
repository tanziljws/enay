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
     * Generate CAPTCHA (return code for display)
     */
    public function generate(Request $request)
    {
        try {
            // Ensure session is started
            if (!$request->hasSession()) {
                Log::warning('CAPTCHA: No session available, starting session');
                $request->session()->start();
            }
            
        // Generate random string
        $captchaText = $this->generateRandomString();
        
            // Store in session with explicit session save
            try {
                $session = $request->session();
                
                // Clear any existing CAPTCHA first to prevent conflicts
                $session->forget('captcha');
                
                // Store new CAPTCHA
                $session->put('captcha', $captchaText);
                
                // Force save session
                $session->save();
                
                // Verify it was saved
                $savedCaptcha = $session->get('captcha');
                
                Log::info('CAPTCHA generated and saved', [
                    'captcha_text' => substr($captchaText, 0, 2) . '***',
                    'captcha_length' => strlen($captchaText),
                    'session_id' => $session->getId(),
                    'has_captcha' => $session->has('captcha'),
                    'saved_captcha' => $savedCaptcha ? substr($savedCaptcha, 0, 2) . '***' : 'null',
                    'saved_matches' => $savedCaptcha === $captchaText
                ]);
            } catch (\Exception $sessionError) {
                Log::error('CAPTCHA Session Error: ' . $sessionError->getMessage(), [
                    'exception' => get_class($sessionError),
                    'trace' => $sessionError->getTraceAsString(),
                    'session_id' => $request->hasSession() ? $request->session()->getId() : 'no-session'
                ]);
                // Continue anyway - CAPTCHA text is still generated
            }
        
        // Return JSON with captcha text for CSS display
        // Even if session save failed, return the CAPTCHA so user can still see it
        $response = response()->json([
            'captcha' => $captchaText,
            'chars' => str_split($captchaText),
            'timestamp' => time()
        ], 200);
            
            // Set headers explicitly
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            $response->headers->set('Content-Type', 'application/json; charset=utf-8');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            
            return $response;
        } catch (\Exception $e) {
            Log::error('CAPTCHA Generation Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorResponse = response()->json([
                'error' => 'Failed to generate CAPTCHA',
                'message' => $e->getMessage()
            ], 500);
            
            $errorResponse->headers->set('Access-Control-Allow-Origin', '*');
            $errorResponse->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $errorResponse->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
            $errorResponse->headers->set('Content-Type', 'application/json; charset=utf-8');
            
            return $errorResponse;
        }
    }
    
    /**
     * Verify CAPTCHA
     */
    public function verify(Request $request)
    {
        try {
            // Ensure session is available
            if (!$request->hasSession()) {
                $request->session()->start();
            }
            
            // TRIM whitespace and convert to uppercase
            $userInput = strtoupper(trim($request->input('captcha', '')));
            $sessionCaptcha = strtoupper(trim($request->session()->get('captcha', '')));
            
            // Detailed logging for debugging
            Log::info('CAPTCHA Verification', [
                'user_input_raw' => $request->input('captcha', ''),
                'user_input_trimmed' => $userInput,
                'user_input_length' => strlen($userInput),
                'session_captcha_raw' => $request->session()->get('captcha', ''),
                'session_captcha_trimmed' => $sessionCaptcha,
                'session_captcha_length' => strlen($sessionCaptcha),
                'match' => $userInput === $sessionCaptcha,
                'session_id' => $request->session()->getId(),
                'session_exists' => $request->session()->has('captcha'),
                'session_all' => $request->session()->all()
            ]);
            
            // Check if session captcha exists
            if (empty($sessionCaptcha)) {
                Log::warning('CAPTCHA Verification Failed: Session CAPTCHA is empty', [
                    'session_id' => $request->session()->getId(),
                    'has_captcha' => $request->session()->has('captcha')
                ]);
                
                $errorResponse = response()->json([
                    'success' => false,
                    'message' => 'Kode CAPTCHA tidak ditemukan. Silakan refresh CAPTCHA.'
                ], 422);
                $errorResponse->headers->set('Access-Control-Allow-Origin', '*');
                $errorResponse->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
                $errorResponse->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
                $errorResponse->headers->set('Access-Control-Allow-Credentials', 'true');
                return $errorResponse;
            }
            
            if ($userInput === $sessionCaptcha && !empty($sessionCaptcha)) {
                // Clear captcha from session
                $request->session()->forget('captcha');
                $request->session()->save();
                
                Log::info('CAPTCHA Verification Success', [
                    'session_id' => $request->session()->getId()
                ]);
                
                $response = response()->json(['success' => true]);
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                return $response;
            }
            
            Log::warning('CAPTCHA Verification Failed: Mismatch', [
                'user_input' => $userInput,
                'session_captcha' => $sessionCaptcha,
                'session_id' => $request->session()->getId()
            ]);
            
            $errorResponse = response()->json([
                'success' => false,
                'message' => 'Kode CAPTCHA salah. Silakan coba lagi.'
            ], 422);
            $errorResponse->headers->set('Access-Control-Allow-Origin', '*');
            $errorResponse->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $errorResponse->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
            $errorResponse->headers->set('Access-Control-Allow-Credentials', 'true');
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
            $errorResponse->headers->set('Access-Control-Allow-Origin', '*');
            $errorResponse->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $errorResponse->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
            $errorResponse->headers->set('Access-Control-Allow-Credentials', 'true');
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
