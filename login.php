<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    echo 'Invalid credentials.';
    exit;
}

// fetch user
$stmt = $mysqli->prepare("SELECT customer_id, password_hash, twofa_enabled FROM customers WHERE email = ? LIMIT 1");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo 'Invalid credentials.';
    exit;
}
$stmt->bind_result($customer_id, $password_hash, $twofa_enabled);
$stmt->fetch();

// verify password
if (!password_verify($password, $password_hash)) {
    echo 'Invalid credentials.';
    exit;
}

// Password OK — check 2FA flag
if ((int)$twofa_enabled === 1) {
    // Placeholder: user has 2FA enabled
    // Redirect to a page to enter 2FA code (not implemented yet)
    // Save temp session / token identifying user and proceed to 2FA verification
    session_start();
    $_SESSION['pre_2fa_user'] = $customer_id;
    header('Location: verify-2fa.html'); // you'll implement this when doing 2FA
    exit;
} else {
    // Log the user in (simple session)
    session_start();
    $_SESSION['user_id'] = $customer_id;
    $_SESSION['user_email'] = $email;
    echo '<p>Login successful. <a href="dashboard.php">Go to dashboard</a></p>';
}
?>
