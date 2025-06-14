<?php
session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if (!$name || !$email || !$password || !$role) {
        $_SESSION['register_error'] = 'All fields are required.';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered.';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
    $stmt->execute();
    $stmt->close();

    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    header("Location: " . ($role === 'admin' ? 'admin_page.php' : 'user_page.php'));
    exit();
}

if (isset($_POST['Login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $_SESSION['login_error'] = 'Email and password are required.';
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            header("Location: " . ($user['role'] === 'admin' ? 'admin_page.php' : 'user_page.php'));
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password.';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
