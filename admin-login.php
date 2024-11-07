<?php
session_start();

// Include the database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin with provided username exists
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // Compare plain-text password
        if ($admin['password'] === $password) {
            // Successful login
            $_SESSION['username'] = $username;
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Admin Login Successful</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f0f0f0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                    }
                    .message-box {
                        background-color: #4CAF50;
                        color: white;
                        padding: 20px;
                        border-radius: 5px;
                        text-align: center;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    }
                    .dashboard-link {
                        margin-top: 15px;
                        display: inline-block;
                        padding: 10px 15px;
                        background-color: white;
                        color: #4CAF50;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                    .dashboard-link:hover {
                        background-color: #ddd;
                    }
                </style>
            </head>
            <body>
                <div class="message-box">
                    <h2>Welcome, ' . htmlspecialchars($admin['username']) . '!</h2>
                    <p>You have successfully logged in as Admin.</p>
                    <a href="admin-interface.html" class="dashboard-link">Go to Admin Dashboard</a>
                </div>
            </body>
            </html>
            ';
        } else {
            // Incorrect password
            displayError("Incorrect username or password. Please try again.");
        }
    } else {
        // Username not found
        displayError("No account found with this username. Please contact support.");
    }
    $stmt->close();
}
$conn->close();

// Function to display error message in HTML format
function displayError($message) {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .error-box {
                background-color: #ff4d4d;
                color: white;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            .error-box a {
                color: white;
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h2>Login Error</h2>
            <p>' . htmlspecialchars($message) . '</p>
            <p><a href="admin-login.html">Back to Login</a></p>
        </div>
    </body>
    </html>
    ';
    exit();
}
?>
