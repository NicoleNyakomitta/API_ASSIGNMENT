<?php
class TwoFactorAuth {
    public function generateSecret() {
        return bin2hex(random_bytes(16));
    }
    
    public function sendCode($phone, $code) {
        return $code;
    }
    
    public function generateCode() {
        return rand(100000, 999999);
    }
    
    public function verifyCode($userCode, $storedCode) {
        return $userCode === $storedCode;
    }
}
?>