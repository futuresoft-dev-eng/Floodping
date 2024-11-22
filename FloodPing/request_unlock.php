<?php
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : ''; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Unlock</title>
    <style>
        .modal {
            display: none;
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

    </style>
</head>
<body>

<h3>Account Locked</h3>
<p>Your account has been locked due to multiple unsuccessful login attempts. Please submit a request to resolve this issue.</p>

<form action="process_unlock_request.php" method="POST">
    <label>User ID</label>
    <input type="text" name="user_id" required placeholder="#####"><br>
    
    <label>Mobile Number</label>
    <input type="text" name="mobile_number" required placeholder="09#########"><br>
    
    <label>First Name</label>
    <input type="text" name="first_name" required placeholder="Enter first name"><br>
    
    <label>Last Name</label>
    <input type="text" name="last_name" required placeholder="Enter your last name"><br>
    
    <label>Email</label>
    <input type="email" name="email" required value="<?= $email ?>" readonly><br>
    
    <button type="submit">Submit Request</button>
</form>

<div id="responseModal" class="modal">
  <div class="modal-content">
    <h4 id="modalTitle">Request Submitted Successfully!</h4>
    <p id="modalMessage"></p>
    <div class="modal-footer">
      <button id="okButton" class="btn">Okay</button>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var status = "<?= $status ?>"; 

        if (status === 'success') {
            document.getElementById('modalMessage').innerText = 'Your request has been received! Please monitor your email for further updates and instructions';
            document.getElementById('responseModal').style.display = 'block';
        } else if (status === 'error') {
            document.getElementById('modalMessage').innerText = 'There was an error sending your request. Please try again later.';
            document.getElementById('responseModal').style.display = 'block';
        }
        document.getElementById('okButton').onclick = function() {
            window.location.href = 'login.php';  
        };
    });
</script>

</body>
</html>
