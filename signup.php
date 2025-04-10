<?php
require_once 'config.php';
session_start();

if(isset($_SESSION['user_id'])) {
    header("Location: chat.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        try {
            // Generate unique phone number
            $stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(phone_number, 6) AS UNSIGNED)) as max_num FROM users");
            $result = $stmt->fetch();
            $next_num = ($result['max_num'] ?? 1000) + 1;
            $phone_number = "+786-" . $next_num;
            
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (name, phone_number, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $phone_number, $hashed_password]);
            
            $success = "Account created successfully! Your WhatsApp number is: " . $phone_number;
        } catch(PDOException $e) {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - WhatsApp Clone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .signup-container {
            background-color: #202c33;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #00a884;
            margin-bottom: 30px;
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

        .success-message {
            color: #00a884;
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

        @media (max-width: 480px) {
            .signup-container {
                padding: 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create Account</h2>

        <?php if($error): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="success-message">
                <?php echo $success; ?>
                <p>Please <a href="login.php">login</a> to continue.</p>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn">Sign Up</button>
            </form>

            <div class="links">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
