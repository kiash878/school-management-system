<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'parent') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Payment.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    
    // Basic Validation
    if (empty($phone) || empty($amount)) {
        echo json_encode(['success' => false, 'message' => 'Phone and Amount are required.']);
        exit;
    }

    // Sanitize phone: Remove everything except numbers
    $phone = preg_replace('/[^0-9]/', '', $phone);

    // Format to 2547...
    if (substr($phone, 0, 1) == '0') {
        $phone = '254' . substr($phone, 1);
    }
    // If it starts with 254, it's fine. If 7..., add 254
    if (strlen($phone) == 9 && substr($phone, 0, 1) == '7') {
         $phone = '254' . $phone;
    }

    $payment = new Payment();
    $result = $payment->initiateSTKPush($phone, $amount);

    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Request Method']);
}
?>
