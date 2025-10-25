<?php
// register.php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.html');
    exit;
}

$first = trim($_POST['first_name'] ?? '');
$last  = trim($_POST['last_name'] ?? '');
$phone = trim($_POST['phone_number'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// Basic server-side validation
$errors = [];

if ($first === '') $errors[] = 'First name required.';
if ($last === '') $errors[] = 'Last name required.';
if (!preg_match('/^(?:\+254|0)?7\d{8}$/', $phone)) $errors[] = 'Phone number invalid.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
if ($password === '' || strlen($password) < 8 || !preg_match('/\d/', $password) || !preg_match('/[A-Za-z]/', $password)) {
    $errors[] = 'Password must be at least 8 chars and include letters and numbers.';
}
if ($password !== $password_confirm) $errors[] = 'Passwords do not match.';

if ($errors) {
    // Show simple error page (you can style better later)
    echo '<h2>Registration error</h2><ul><li>' . implode('</li><li>', array_map('htmlspecialchars', $errors)) . '</li></ul>';
    echo '<p><a href="register.html">Go back</a></p>';
    exit;
}

// check if email or phone already exists
$stmt = $mysqli->prepare("SELECT customer_id FROM customers WHERE email = ? OR phone_number = ? LIMIT 1");
if (!$stmt) { die('Prepare failed: ' . $mysqli->error); }
$stmt->bind_param('ss', $email, $phone);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo '<p>An account with that email or phone already exists. <a href="register.html">Back</a></p>';
    exit;
}
$stmt->close();

// hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// insert user
$insert = $mysqli->prepare("INSERT INTO customers (first_name, last_name, phone_number, email, password_hash) VALUES (?, ?, ?, ?, ?)");
if (!$insert) { die('Prepare failed: ' . $mysqli->error); }
$insert->bind_param('sssss', $first, $last, $phone, $email, $hash);

if ($insert->execute()) {
    echo '<h2>Registration successful</h2><p>You can now <a href="login.html">log in</a>.</p>';
} else {
    echo '<h2>Registration failed</h2><p>Please try again later.</p>';
}
$insert->close();
$mysqli->close();
?>
