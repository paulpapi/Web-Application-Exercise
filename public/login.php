<?php
require_once __DIR__ . '/../config/db.php';

// session_start();
// //echo "<p>Session is still set to: " . $_SESSION['username'] . "</p>";

// session_abort();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // GET THE USERNAME AND PASSWORD FROM THE FORM
    $username = $_POST["username"];
    $password = $_POST["password"];
    // CHECK IF THE USERNAME AND PASSWORD MATCH AND IF THEY EXIST IN DATABASE
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $select = mysqli_prepare($conn, $sql);
    $select->bind_param("ss", $username, $password);
    $select->execute();
    $result = $select->get_result();

    // REMOVE EXISTING SESSIONS 
    if(isset($_SESSION['username'])){
        echo "session was: " . $_SESSION['username'] . "<br/>";
        $_SESSION = array();
        echo "session is: " . $_SESSION['username'] . "<br/>";
        session_destroy();
        $_COOKIE = array();
    }

    session_start();
    
    // CREATES SESSIONS AND COOKIES IF THE MATCHING USERNAME AND PASSWORD  IS FOUND IN DATABASE
    if($row = mysqli_fetch_assoc($result)){
        $cookiename = "User";
        $cookievalue = $username;

        setcookie($cookiename, $cookievalue, time() + 3600);
        $_SESSION["username"] = $username;


        if(!isset($_COOKIE[$cookiename])){
            echo "Cookie: " . $cookiename . " is not set.";

            $_SESSION["username"] = $username;
        }else {
            echo "Cookie: " . $cookiename . " is set.";
            echo "Value is " . $_COOKIE[$cookiename];
        }

        header("Location: index.php?username=$username");

    }else{
        echo "<h1>The Provided information is invalid.</h1>";
    }

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
            <form class="login-form" action="login.php" method="POST">
            <h2>Login</h2>
            <input type="text" placeholder="Username" name="username" required />
            <input type="password" placeholder="Password" name="password" required />
            <button type="submit">Login</button>
            <p class="signup-link">Don't have an account? <a href="signup.php">Sign up</a></p>
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

          });

        </script>

  </body>
</html>
