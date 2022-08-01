<?php

namespace vilija19\web3login\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use vilija19\web3login\Web3;

class Web3AuthController extends Controller
{
    /**
     * Create authenticate user.
     * @param Request $request
     *
     * @return void
     */
    public function authenticate(Request $request)
    {
        Web3::verifySignature(
            $this->getSignatureMessage(session()->get('metamask-nonce')),
            $request->signature,
            $request->address,
        );
    
        $user = User::query()->where('eth_address', $request->address)->first();

        session()->forget('metamask-nonce');

        if (!$user && config('web3login.strict_mode') == false) {
            $user = new User();
            $user->name = 'metamask-user';
            $user->email = $request->address;
            $user->password = Str::random(16);
            $user->eth_address = $request->address;
            $user->save();
            auth()->login($user);
        }elseif ($user) {
            auth()->login($user);
        }else {
            return back()->withErrors([
                'email' => trans('auth.failed'),
            ]);
        }
    
        return true;
    }

    /**
     * Create signature message.
     * @param Request $request
     *
     * @return string
     */
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
