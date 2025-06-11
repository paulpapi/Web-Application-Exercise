<?php
require_once __DIR__ . '/../config/db.php';

$errors = [];
$success = false;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate and sanitize inputs
    $fullname = trim($_POST["fullname"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $phone = trim($_POST["phone"] ?? '');
    $password = trim($_POST["password"] ?? '');
    
    // Validate fullname
    if(empty($fullname)) {
        $errors['fullname'] = "Full name is required";
    } elseif(strlen($fullname) > 50) {
        $errors['fullname'] = "Full name must be 50 characters or less";
    }
    // Validate username
    if(empty($username)) {
        $errors['username'] = "Username is required";
    } elseif(strlen($username) > 50) {
        $errors['username'] = "Username must be 50 characters or less";
    } else {
        // Check if username already exists
        $check = mysqli_prepare($conn, "SELECT uid FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();
        if($check->num_rows > 0) {
            $errors['username'] = "Username already taken";
        }
        $check->close();
    }
    
    // Validate phone
    if(empty($phone)) {
        $errors['phone'] = "Phone number is required";
    } elseif(!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
        $errors['phone'] = "Please enter a valid phone number (10-15 digits, + optional)";
    } elseif(strlen($phone) > 24) {
        $errors['phone'] = "Phone number must be 24 characters or less";
    }
    
    // Validate password
    if(empty($password)) {
        $errors['password'] = "Password is required";
    } elseif(strlen($password) < 4) {
        $errors['password'] = "Password must be at least 4 characters";
    } elseif(strlen($password) > 20) {
        $errors['password'] = "Password must be 20 characters or less";
    }
    
  // save the user to db beka
    $sql = "INSERT INTO users(fullname, username, phone, password) VALUES(?, ?, ?, ?);";
    $insert = mysqli_prepare($conn, $sql);
    $insert->bind_param("ssss", $fullname, $username, $phone, $password);
    $insert->execute();
    $success = TRUE;
  
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PL - Your Online Public Listing Website</title>
    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400"> 
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">                
    <link rel="stylesheet" href="css/bootstrap.min.css">                                      
    <link rel="stylesheet" href="css/pl-orig-style.css">                                   
    <link rel="stylesheet" href="css/login-page.css">
    <style>
        .error {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }
        .success-message {
            color: green;
            font-size: 1.1em;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e8f5e9;
            border-radius: 4px;
        }
        input.error-highlight {
            border-color: red;
        }
    </style>
  </head>

  <body>
    <div class="container">
      <header class="tm-site-header">
        <h1 class="tm-site-name">PL</h1>
        <p class="tm-site-description">Your Online Public Listings Website</p><br/><br/>

        <nav class="navbar navbar-expand-md tm-main-nav-container">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#tmMainNav" aria-controls="tmMainNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
          </button>

          <div class="collapse navbar-collapse tm-main-nav" id="tmMainNav">
            <ul class="nav nav-fill tm-main-nav-ul">
              <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
            </ul>
          </div>
        </nav>
      </header>

      <div class="tm-main-content">
        <div class="login-container">
            <?php if($success): ?>
                <div class="success-message">
                    Account created successfully! You can now <a href="login.php">login</a>.
                </div>
            <?php elseif(!empty($errors['database'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['database']) ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="signup.php" id="signup-form">
                <h2>Sign Up</h2>
                
                <input type="text" name="fullname" placeholder="Full Name" 
                       value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" 
                       class="<?= !empty($errors['fullname']) ? 'error-highlight' : '' ?>" required />
                <?php if(!empty($errors['fullname'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['fullname']) ?></div>
                <?php endif; ?>
                
                <input type="text" name="username" placeholder="Username" 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                       class="<?= !empty($errors['username']) ? 'error-highlight' : '' ?>" required />
                <?php if(!empty($errors['username'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['username']) ?></div>
                <?php endif; ?>
                
                <input type="tel" name="phone" placeholder="Phone Number" 
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" 
                       class="<?= !empty($errors['phone']) ? 'error-highlight' : '' ?>" required />
                <?php if(!empty($errors['phone'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['phone']) ?></div>
                <?php endif; ?>
                
                <input type="password" name="password" placeholder="Password" 
                       class="<?= !empty($errors['password']) ? 'error-highlight' : '' ?>" required />
                <?php if(!empty($errors['password'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['password']) ?></div>
                <?php endif; ?>
                
                <button type="submit">Create Account</button>
                <p class="signup-link">Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
      </div>

      <footer>
          Copyright &copy; <span class="tm-current-year">2018</span> Nobody.
          - Designed by <a href="https://www.facebook.com/" target="_parent">The Group</a>
      </footer>
    </div>

    <!-- load JS files -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <script>
        $(document).ready(function(){
            // Update the current year in copyright
            $('.tm-current-year').text(new Date().getFullYear());
            
            // Form validation
            $('#signup-form').submit(function(e) {
                let isValid = true;
                const errors = {};
                
                // Clear previous error highlights
                $('.error-highlight').removeClass('error-highlight');
                $('.error').remove();
                
                // Validate fullname
                const fullname = $('input[name="fullname"]').val().trim();
                if (!fullname) {
                    errors.fullname = 'Full name is required';
                    isValid = false;
                    $('input[name="fullname"]').addClass('error-highlight');
                } else if (fullname.length > 50) {
                    errors.fullname = 'Full name must be 50 characters or less';
                    isValid = false;
                    $('input[name="fullname"]').addClass('error-highlight');
                }
                
                // Validate username
                const username = $('input[name="username"]').val().trim();
                if (!username) {
                    errors.username = 'Username is required';
                    isValid = false;
                    $('input[name="username"]').addClass('error-highlight');
                } else if (username.length > 50) {
                    errors.username = 'Username must be 50 characters or less';
                    isValid = false;
                    $('input[name="username"]').addClass('error-highlight');
                }
                
                // Validate phone
                const phone = $('input[name="phone"]').val().trim();
                const phoneRegex = /^\+?[0-9]{10,15}$/;
                if (!phone) {
                    errors.phone = 'Phone number is required';
                    isValid = false;
                    $('input[name="phone"]').addClass('error-highlight');
                } else if (!phoneRegex.test(phone)) {
                    errors.phone = 'Please enter a valid phone number (10-15 digits, + optional)';
                    isValid = false;
                    $('input[name="phone"]').addClass('error-highlight');
                } else if (phone.length > 24) {
                    errors.phone = 'Phone number must be 24 characters or less';
                    isValid = false;
                    $('input[name="phone"]').addClass('error-highlight');
                }
                
                // Validate password
                const password = $('input[name="password"]').val();
                if (!password) {
                    errors.password = 'Password is required';
                    isValid = false;
                    $('input[name="password"]').addClass('error-highlight');
                } else if (password.length < 4) {
                    errors.password = 'Password must be at least 4 characters';
                    isValid = false;
                    $('input[name="password"]').addClass('error-highlight');
                } else if (password.length > 20) {
                    errors.password = 'Password must be 20 characters or less';
                    isValid = false;
                    $('input[name="password"]').addClass('error-highlight');
                }
                
                if (!isValid) {
                    e.preventDefault();
                    // Display errors to user
                    for (const [field, message] of Object.entries(errors)) {
                        $(`input[name="${field}"]`).after(`<div class="error">${message}</div>`);
                    }
                }
            });
        });
    </script>
  </body>
</html>