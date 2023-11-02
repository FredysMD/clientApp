<?php 

class JWT 
{ 
    private static $instance;
    private $secretKey;
     
    public function __construct($secretKey){ 
        $this->secretKey = $secretKey;
    }

    public static function getInstance($secretKey){
        if (!self::$instance) {
            self::$instance = new JWT($secretKey);
        }
        
        return self::$instance;
    }
    
    public function generateToken($payload, $expiration){

        $header = $this->base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = $this->base64UrlEncode(json_encode($payload));
        $signature = $this->generateSignature($header, $payload);
        
        return $header . '.' . $payload . '.' . $signature;
    }
    
    private function base64UrlEncode($data){
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    private function generateSignature($header, $payload){
        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->secretKey, true);
        return $this->base64UrlEncode($signature);
    }
}