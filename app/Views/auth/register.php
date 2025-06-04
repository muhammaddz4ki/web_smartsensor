<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Sensor Net</title>
    <link rel="stylesheet" href="<?= base_url('css/register.css') ?>">
</head>
<body>
    <nav class="navbar">
        <div class="logo-container">
            <img src="/assets/images/landing.png" alt="Smart Sensor Net Logo" class="logo-img">
            <span class="brand-name">Smart Sensor Net</span>
        </div>
        <div class="nav-buttons">
            <a href="/login" class="btn btn-login">Login</a>
        </div>
    </nav>
    
    <div class="register-container">
        <form class="register-form" action="/register" method="post">
            <h2 class="form-title">Create Account</h2>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <span class="alert-icon">⚠️</span>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Choose your username" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
            </div>
            <button type="submit" class="btn-submit">Register</button>
            <div class="form-footer">
                Already have an account? <a href="/login">Sign in</a>
            </div>
        </form>
    </div>
    
    <footer>
        <div class="copyright">
            &copy; Created with 💡 by 2 AEC 2 – Smart Sensor Net 2025
        </div>
    </footer>
</body>
</html>
