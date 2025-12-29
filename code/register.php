<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SmartMail AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body class="auth-page">
    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-lg fade-in" style="width: 100%; max-width: 400px;">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4">📧 SmartMail AI</h2>
                <h4 class="text-center mb-4">Create Account</h4>
                
                <div id="alert-container"></div>
                
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required minlength="6">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
                </form>
                
                <p class="text-center mt-3">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/ajax.js"></script>
</body>
</html>

