<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Sensor Net</title>
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
</head>
<body>
    <nav class="navbar">
        <div class="logo-container">
            <img src="/assets/images/landing.png" alt="Smart Sensor Net Logo" class="logo-img">
            <span class="brand-name">Smart Sensor Net</span>
        </div>
    </nav>
    
    <div class="login-container">
        <form class="login-form" action="/login" method="post">
            <h2 class="form-title">Login</h2>
            
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <span class="alert-icon">⚠️</span>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="login">Username or Email</label>
                <input type="text" id="login" name="login" class="form-control" 
                       placeholder="Enter your username or email" 
                       value="<?= old('login') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <div class="form-footer">
                Don't have an account? <a href="/register">Register here</a>
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
