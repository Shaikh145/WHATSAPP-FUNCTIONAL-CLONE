<?php
require_once 'config.php';
session_start();

if(isset($_SESSION['user_id'])) {
    header("Location: chat.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone_number = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();
        
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['phone_number'] = $user['phone_number'];
            header("Location: chat.php");
            exit();
        } else {
            $error = "Invalid phone number or password";
        }
    } catch(PDOException $e) {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - WhatsApp Clone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #111b21;
            color: #e9edef;
            line-height: 1.6;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: #202c33;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            height: 60px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #8696a0;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #374045;
            border-radius: 5px;
            background-color: #2a3942;
            color: #e9edef;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #00a884;
        }

        .error-message {
            color: #ff6b6b;
            margin-bottom: 20px;
            text-align: center;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: #00a884;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #008f72;
        }

        .links {
            margin-top: 20px;
            text-align: center;
        }

        .links a {
            color: #00a884;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #00a884;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-btn:hover {
            color: #008f72;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>

    <div class="login-container">
        <div class="logo">
            <img src="whatsapp-logo.png" alt="WhatsApp Logo">
        </div>

        <?php if($error): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="+786-XXXX" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="links">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
