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
            // Generate random string
            $captchaText = $this->generateRandomString();
            
            // Store in session with explicit session save
            $request->session()->put('captcha', $captchaText);
            $request->session()->save();
            
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
        $userInput = strtoupper($request->input('captcha'));
        $sessionCaptcha = strtoupper(session('captcha', ''));
        
        if ($userInput === $sessionCaptcha) {
            // Clear captcha from session
            session()->forget('captcha');
            return response()->json(['success' => true])
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Kode CAPTCHA salah'
        ], 422)->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
          ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
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
