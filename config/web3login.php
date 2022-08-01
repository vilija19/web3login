<?php

return [
    /**
     * Web3Login configuration.
     * 
     * strict_mode - if true, the login will be performed in strict mode.
     * It means that user must do the usual registration 
     * and provides Ethereum address to his profile.
     * In next logins, will be allowed login by CryptoWallet
     * 
     * If false - any user can login by CryptoWallet. The first login assumes registration a new user. 
     * After user can edit his profile if he wants (Complete name,email etc).
     */

    'strict_mode' => false,
];