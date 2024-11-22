<?php
session_start();
include_once('../db/db_conn.php');
date_default_timezone_set('Asia/Manila');

$error = $success = "";
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_POST['token'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9])[\S]{8,}$/', $password)) {
        $error = "Password does not meet the requirements.";
    } else {
        $stmt = $conn->prepare("SELECT email, expiry FROM password_resets WHERE reset_token = ? AND expiry > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $email = $row['email'];
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $hashed_password, $email);
            if ($updateStmt->execute()) {
                $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE reset_token = ?");
                $deleteStmt->bind_param("s", $token);
                $deleteStmt->execute();

                $success = "Password reset successful. You can now log in.";
            } else {
                $error = "Failed to reset password. Please try again.";
            }
            $updateStmt->close();
        } else {
            $error = "Invalid or expired reset token.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.querySelector("input[name='password']");
            const confirmPasswordInput = document.querySelector("input[name='confirm_password']");
            const feedback = document.getElementById("feedback");
            const matchFeedback = document.getElementById("matchFeedback");

            passwordInput.addEventListener("input", function () {
                const password = passwordInput.value;
                const requirements = [
                    { regex: /.{8,}/, message: "At least 8 characters long" },
                    { regex: /[A-Z]/, message: "At least one uppercase letter" },
                    { regex: /[a-z]/, message: "At least one lowercase letter" },
                    { regex: /\d/, message: "At least one number" },
                    { regex: /[^A-Za-z0-9]/, message: "At least one special character" }
                ];

                const unmet = requirements.filter(req => !req.regex.test(password));
                feedback.innerHTML = unmet.length
                    ? "<ul>" + unmet.map(req => `<li>${req.message}</li>`).join("") + "</ul>"
                    : "Password meets all requirements!";
                feedback.style.color = unmet.length ? "red" : "green";
            });

            confirmPasswordInput.addEventListener("input", function () {
                matchFeedback.textContent = passwordInput.value === confirmPasswordInput.value
                    ? "Passwords match!"
                    : "Passwords do not match!";
                matchFeedback.style.color = passwordInput.value === confirmPasswordInput.value ? "green" : "red";
            });

            <?php if ($success): ?>
                const modal = document.getElementById('successModal');
                modal.style.display = 'block';
            <?php endif; ?>
        });
        

        function openModal() {
    document.getElementById('successModal').style.display = 'flex';
} 

function closeModal() {
    document.getElementById('successModal').style.display = 'none';
}
    </script>
    <style>
#successModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    text-align: center;
    justify-content: center;
    align-items: center;
    display: flex;
}

.modal-content {
    background: #fff; 
    padding: 20px;
    border-radius: 5px; 
    text-align: center;
    width: 300px; 
    max-width: 90%; 
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    color: #aaa;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
}

    </style>
</head>
<body>
    <h3>Set your new password</h3>
    <p>You're almost there! Create a strong, secure password to keep your account safe.</p>
    <form action="" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label>New Password:</label>
        <input type="password" name="password" required><br>
        <div id="feedback" style="color: red;"></div>
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required><br>
        <div id="matchFeedback" style="color: red;"></div>
        <button type="submit">Reset Password</button>
    </form>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

<div id="successModal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h4>Password reset successful!</h4>
        <p>Your password has been reset. You can now log in with your new password.</p>
        <button onclick="window.location.href = 'login.php';">OK</button>
    </div>
</div>

</body>
</html>
