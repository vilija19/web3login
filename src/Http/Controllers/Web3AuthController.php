<?php

namespace vilija19\web3login\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use vilija19\web3login\Web3;

class Web3AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        Web3::verifySignature(
            $this->getSignatureMessage(session()->get('metamask-nonce')),
            $request->signature,
            $request->address,
        );
    
        $user = User::query()->where('eth_address', $request->address)->first();

        if (!$user) {
            $user = new User();
            $user->name = 'metamask-user';
            $user->email = $request->address;
            $user->password = Str::random(16);
            $user->eth_address = $request->address;
            $user->save();
        }
    
        auth()->login($user);
    
        session()->forget('metamask-nonce');
    
        return true;
    }
     
    public function signature(Request $request)
    {
        // Generate some random nonce
        $code = Str::random(8);
        
        // Save in session
        session()->put('metamask-nonce', $code);
        
        // Create message with nonce
        return $this->getSignatureMessage($code);     
    
    }
    private function getSignatureMessage($code)
    {
        return __("I have read and accept the terms and conditions.\nPlease sign me in.\n\nSecurity code (you can ignore this): :nonce", [
            'nonce' => $code
        ]);
    }
}
