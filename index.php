<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: chat.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WhatsApp Web Clone</title>
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #00a884;
            padding: 16px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .logo h1 {
            color: white;
            font-size: 24px;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-login {
            background-color: #ffffff;
            color: #00a884;
        }

        .btn-signup {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .main-content {
            margin-top: 100px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 40px 0;
        }

        .hero-text {
            flex: 1;
            padding-right: 50px;
        }

        .hero-text h2 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #00a884;
        }

        .hero-text p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .hero-image {
            flex: 1;
            text-align: center;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .features {
            margin-top: 60px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .feature-card {
            background-color: #202c33;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        .feature-card i {
            font-size: 40px;
            color: #00a884;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #e9edef;
        }

        .feature-card p {
            color: #8696a0;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            padding: 20px;
            background-color: #202c33;
        }

        .footer p {
            color: #8696a0;
        }

        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
                text-align: center;
            }

            .hero-text {
                padding-right: 0;
                margin-bottom: 40px;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .nav-buttons {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="whatsapp-logo.png" alt="WhatsApp Logo">
                <h1>WhatsApp Web Clone</h1>
            </div>
            <div class="nav-buttons">
                <a href="login.php" class="btn btn-login">Login</a>
                <a href="signup.php" class="btn btn-signup">Sign Up</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <div class="hero-text">
                <h2>Connect with Everyone</h2>
                <p>Experience seamless communication with our WhatsApp Web Clone. Send messages, share media, and stay connected with your friends and family across the globe.</p>
                <a href="signup.php" class="btn btn-login">Get Started</a>
            </div>
            <div class="hero-image">
                <img src="chat-preview.png" alt="WhatsApp Preview">
            </div>
        </div>

        <div class="features">
            <div class="feature-card">
                <i class="fas fa-comments"></i>
                <h3>Real-time Messaging</h3>
                <p>Send and receive messages instantly with our real-time messaging system.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-user-shield"></i>
                <h3>Secure Communication</h3>
                <p>Your messages are secure and private with our advanced security features.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-users"></i>
                <h3>Easy to Connect</h3>
                <p>Find and connect with friends using their unique WhatsApp numbers.</p>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> WhatsApp Web Clone. All rights reserved.</p>
    </footer>
</body>
</html>
