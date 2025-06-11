<?php 
require __DIR__ . "/../config/db.php";

$ownerName = $_GET['ownerName'] ?? 'unknown';

$sql = "SELECT * FROM users WHERE username=?;";
$select = mysqli_prepare($conn, $sql);
$select->bind_param("s", $ownerName);
$select->execute();
$result = $select->get_result();
$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PL - Your Online Public Listing Website</title>

    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">  <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">                <!-- Font Awesome -->
    <link rel="stylesheet" href="css/bootstrap.min.css">                                      <!-- Bootstrap style -->
    <link rel="stylesheet" href="css/pl-orig-style.css">                                   <!-- Templatemo style -->
    <link rel="stylesheet" href="css/profile.css">  

</head>

    <body>
        
        <div class="container">
            <header class="tm-site-header">
            <h1 class="tm-site-name">PL</h1>
            <p class="tm-site-description">Your Online Public Listings Website</p>
                
                <nav class="navbar navbar-expand-md tm-main-nav-container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#tmMainNav" aria-controls="tmMainNav" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse tm-main-nav" id="tmMainNav">
                        <ul class="nav nav-fill tm-main-nav-ul">
                            <li class="nav-item"><a class="nav-link" href="index.php    ">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="addListing.php">Add Listing</a></li>
                            <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                        </ul>
                    </div>    
                </nav>
                
            </header>

            <?php
                $fullname = $row['fullname'];
                $username = $row['username'];
                $phone = $row['phone'];

                echo <<<HTML
                <div class="tm-gallery no-pad-b">
                <div class="profile-container">
                    <div class="profile-card">
                    <h1 class="profile-name">Full Name: $fullname.</h1>
                    <p class="profile-bio">Username: $username.</p>
                    <div class="profile-contact">
                        <p>Phone: $phone</p>
                    </div>
                    </div>
                </div>
                </div>
                HTML;
            ?>
            
                                        
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