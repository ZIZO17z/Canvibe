<?php
session_start();


$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';


session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message' aria-live='polite'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/icons8-c-64.png" type="image/png" />
    <link rel="stylesheet" href="styles/login.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
</head>

<body>
    <video autoplay muted loop playsinline id="bg-video">
        <source src="original-d99071d642c8efd87400222a0c344e1a.mp4" type="video/mp4" />
    </video>

    <div class="login-container">
        <div class="form-box <?= isActiveForm('login', $activeForm) ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Login</h2>
                <?= showError($errors['login']) ?>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="Login">Login</button>
                <p>Don't have an account? <a href="#" onclick="showForm(event, 'register-form')">Register</a></p>
            </form>
        </div>

        <div class="form-box <?= isActiveForm('register', $activeForm) ?>" id="register-form">
            <form action="login_register.php" method="post">
    <h2>Register</h2>
    <?= showError($errors['register']) ?>
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role" required>
        <option value="">Select role</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select>
    <button type="submit" name="register">Register</button>
    <p>Already have an account? <a href="#" onclick="showform('login-form')">Login</a></p>
</form>

        </div>
    </div>

    <script>
        function showForm(event, formId) {
            event.preventDefault();
            document.querySelectorAll('.form-box').forEach(form => form.classList.remove('active'));
            document.getElementById(formId).classList.add('active');
        }
    </script>
</body>

</html>
