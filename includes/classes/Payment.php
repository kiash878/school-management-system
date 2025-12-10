<?php

class Payment {
    private $api_key;
    private $api_url = 'https://api.lipana.dev/v1/transactions/push-stk';

    public function __construct() {
        $this->api_key = LIPANA_API_KEY;
    }

    public function initiateSTKPush($phone, $amount) {
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'phone' => $phone,
            'amount' => $amount
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . $this->api_key,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL check for local dev env
        
        $response = curl_exec($ch);
        
        // Log the response for debugging
        file_put_contents('../payment_debug.log', date('Y-m-d H:i:s') . " Phone: $phone Amount: $amount Response: " . $response . "\n", FILE_APPEND);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['success' => false, 'message' => 'cURL Error: ' . $error_msg];
        }
        
        curl_close($ch);

        $result = json_decode($response, true);
        
        if ($http_code >= 200 && $http_code < 300) {
             return ['success' => true, 'data' => $result];
        } else {
             return ['success' => false, 'message' => $result['message'] ?? 'Unknown Error', 'details' => $result];
        }
    }
}
?>
