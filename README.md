web3login
=====================

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vilija19/web3login.svg?style=flat-square)](https://packagist.org/packages/vilija19/web3login)
[![Total Downloads](https://img.shields.io/packagist/dt/vilija19/web3login.svg?style=flat-square)](https://packagist.org/packages/vilija19/web3login)

Laravel package for login user by Crypto Wallet (Metamask)  
Based on this [article](https://medium.com/geekculture/laravel-authentication-using-web3-15d0fb030a48).

Description
------------
The main goal of this package is to simplify user login and use Web3 approach for authentification.  
I see two scenarios for authentification.  
* First (done in this package)  
Any user can login by CryptoWallet. The first login assumes registration a new user.
After user can edit his profile if he wants (Complete name,email etc).  
* Second  
More strict. At first, user must do usual registration and provides Ethereum address to his profile.  
In next logins, he will be able login by CryptoWallet  

This behaviour is determined in the file **src/Http/Controllers/Web3AuthController.php** method **authenticate()** section `if (!$user) {`  

Installation
------------

You can install the package via composer:

```bash
composer require vilija19/web3login
```
This packege will install laravel/breeze if it not intalled yet for basic user authentification.  
If breeze was not installed before you should do [some actions](https://laravel.com/docs/8.x/starter-kits#laravel-breeze-installation) to finish install it:  
```
php artisan breeze:install
 
npm install
npm run dev (npm run watch-poll)
```
If `npm run dev` is not works properly (login page has broken styles) try `npm run watch-poll`  

Next install Web3:  
```
npm install web3
```

In file **resources/js/app.js** add this:
```
import Web3 from 'web3/dist/web3.min.js'
window.Web3 = Web3;
```
Compile it by `npm run dev` or `npm run watch-poll`  

Usage
-----------
In file **resources/views/auth/login.blade.php** add this after `</form>` tag:  
```
<div class="text-center py-8 border-t border-gray-200" x-data="{
    loading: false,
    loginSignatureUrl: '{{ route('metamask.signature') }}',
    loginUrl: '{{ route('metamask.authenticate') }}',
    redirectUrl : '/dashboard',
}">
    <button x-bind:disabled="loading" @click="async () => {
        loading = true;     
        
        const web3 = new Web3(window.ethereum);                  
        
        // Fetch nonce
        const message = (await axios.get(loginSignatureUrl)).data;
        // Get wallet address
        const address = (await web3.eth.requestAccounts())[0];
        // Sign message
        const signature = await web3.eth.personal.sign(message, address);
        
        try {
           let response = await axios.post(loginUrl, {
               'address': address,
               'signature': signature,
           });
          
           window.location.href = redirectUrl;
        } catch(e) {
           alert(e.message);
        }
                                              
        loading = false;                                      
    }" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
        Login with MetaMask
    </button>
</div>
```



License
----------
The MIT License (MIT). 

