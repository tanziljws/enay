<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    /**
     * Generate CAPTCHA (return code for display)
     */
    public function generate()
    {
        // Generate random string
        $captchaText = $this->generateRandomString();
        
        // Store in session
        session(['captcha' => $captchaText]);
        
        // Return JSON with captcha text for CSS display
        return response()->json([
            'captcha' => $captchaText,
            'chars' => str_split($captchaText)
        ]);
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
            return response()->json(['success' => true]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Kode CAPTCHA salah'
        ], 422);
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
