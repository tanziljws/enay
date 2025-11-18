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
    public function generate()
    {
        try {
            // Generate random string
            $captchaText = $this->generateRandomString();
            
            // Store in session
            session(['captcha' => $captchaText]);
            
            // Return JSON with captcha text for CSS display
            return response()->json([
                'captcha' => $captchaText,
                'chars' => str_split($captchaText)
            ], 200, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, X-CSRF-TOKEN',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate CAPTCHA',
                'message' => $e->getMessage()
            ], 500, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, X-CSRF-TOKEN',
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
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
