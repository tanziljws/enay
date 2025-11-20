<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                \Log::warning('CAPTCHA: No session available, starting session');
                $request->session()->start();
            }
            
        // Generate random string
        $captchaText = $this->generateRandomString();
        
            // Store in session with explicit session save
            try {
                $session = $request->session();
                $session->put('captcha', $captchaText);
                $session->save();
                \Log::info('CAPTCHA generated and saved: ' . substr($captchaText, 0, 2) . '***', [
                    'session_id' => $session->getId(),
                    'has_captcha' => $session->has('captcha')
                ]);
            } catch (\Exception $sessionError) {
                \Log::error('CAPTCHA Session Error: ' . $sessionError->getMessage(), [
                    'exception' => get_class($sessionError),
                    'trace' => $sessionError->getTraceAsString()
                ]);
                // Try to continue, but log the issue
            }
        
        // Return JSON with captcha text for CSS display
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
            \Log::error('CAPTCHA Generation Error: ' . $e->getMessage());
            
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
            
            $userInput = strtoupper($request->input('captcha', ''));
            $sessionCaptcha = strtoupper($request->session()->get('captcha', ''));
            
            \Log::info('CAPTCHA Verification', [
                'user_input' => substr($userInput, 0, 2) . '***',
                'session_captcha' => substr($sessionCaptcha, 0, 2) . '***',
                'match' => $userInput === $sessionCaptcha,
                'session_id' => $request->session()->getId()
            ]);
            
            if ($userInput === $sessionCaptcha && !empty($sessionCaptcha)) {
            // Clear captcha from session
                $request->session()->forget('captcha');
                $request->session()->save();
                
                $response = response()->json(['success' => true]);
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                return $response;
            }
            
            $errorResponse = response()->json([
            'success' => false,
            'message' => 'Kode CAPTCHA salah'
        ], 422);
            $errorResponse->headers->set('Access-Control-Allow-Origin', '*');
            $errorResponse->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $errorResponse->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-CSRF-TOKEN, Accept');
            $errorResponse->headers->set('Access-Control-Allow-Credentials', 'true');
            return $errorResponse;
        } catch (\Exception $e) {
            \Log::error('CAPTCHA Verify Error: ' . $e->getMessage());
            
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
