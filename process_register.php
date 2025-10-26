<?php
require_once 'Database.php';
require_once 'User.php';
require 'vendor/autoload.php'; // for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

// Collect data
$user->fullname = $_POST['fullname'];
$user->email = $_POST['email'];
$user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$user->otp = rand(100000, 999999);

// Save user
if($user->register()) {
    // Send OTP via email
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com';
    $mail->Password = 'your_app_password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your_email@gmail.com', 'OOP PHP Lab');
    $mail->addAddress($user->email);
    $mail->Subject = 'Your 2FA Code';
    $mail->Body = "Hello {$user->fullname}, your OTP code is {$user->otp}.";

    if($mail->send()) {
        header("Location: verify_otp.php?email=" . urlencode($user->email));
    } else {
        echo "Error sending OTP.";
    }
} else {
    echo "Registration failed.";
}
?>