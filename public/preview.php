<?php 
require __DIR__ . "/../config/db.php";

$pid = $_GET['pid'] ?? 'unknown';

$sql = "SELECT * FROM products WHERE pid=?;";
$select = mysqli_prepare($conn, $sql);
$select->bind_param("d", $pid);
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
            
            <div class="tm-main-content no-pad-b">
        <?php
            $name = $row['name'];
            $price = $row['price'];
            $sdesc = $row['sdesc'];
            $ldesc = $row['ldesc'];
            $category = $row['category'];
            $image = $row['mimage'];
            $src = "uploads/" . $category . "/" . $image;
            $ownerName = $row['ownerName'];

            echo <<<HTML
                <section class="row tm-item-preview">
                    <div class="col-md-6 col-sm-12 mb-md-0 mb-5">
                        <img src=$src alt="Image" class="img-fluid tm-img-center-sm img-prev" width=600, height=700 >
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h2 class="tm-blue-text tm-margin-b-p">$name</h2>
                        <p class="tm-margin-b-p">$sdesc</p>
                        <p class="tm-margin-b-p">$ldesc</p>
                        <p class="tm-margin-b-p"></p>
                        <p class="tm-blue-text tm-margin-b-s">Price: $price</p>
                        <p class="tm-blue-text tm-margin-b-s">Categories: <a href="#" class="tm-blue-text">$category</a></p>
                        <a href="profile.php?ownerName=$ownerName" class="tm-btn tm-btn-gray tm-margin-r-20 tm-margin-b-s">$ownerName Profile</a><a href="profile.php?ownerName=$ownerName" class="tm-btn tm-btn-blue">Phone Number</a>
                    </div>
                </section>
                HTML;
        ?>

                <div class="tm-gallery no-pad-b">
                    
                    <div class="row">
                    <?php
                        $itemsPerPage = 4;
                        $i = 0;
                        
                        $offset = rand(0, 20);
                        // $offset = $_GET["offset"] ?? 0;
                        $sql = "SELECT * FROM products LIMIT " . $itemsPerPage . " OFFSET " . $offset . ";";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($result)){
                            $category = $row['category'];
                            $image = "uploads/" . $category . "/" .$row['mimage'];
                            $name = $row['name'];
                            $price = $row['price'];
                            $sdesc = $row['sdesc'];
                            $href = "preview.php?pid=" . $row['pid'];
                            //echo $pid;
                            $price = number_format($price, 2);
                            echo <<<HTML
                                <figure class="col-lg-3 col-md-4 col-sm-6 col-12 tm-gallery-item">
                                    <a href=$href>
                                    <div class="tm-gallery-item-overlay">
                                        <img src="$image" alt="Image" class="img-fluid tm-img-center" width="250" height="360">
                                    </div>
                                <div class="details-container">   <div class="product-title"><span style="font-weight: bold;">Name:</span> $name</div>
                                    <div class="product-price"><span style="font-weight: bold">Price:</span> $price ETB</div>
                                    <div class="product-description"> </div>
                                    $sdesc;
                                    </div>
                                    </a>
                                </figure>

                            HTML;
                            $i++;
                        }
                    ?>
                    </div>   
                </div>                    
                            
            </div>

            <footer>
                Copyright &copy; <span class="tm-current-year">2018</span> Nobody.

                - Designed by <a href="https://www.facebook.com/" target="_parent">The Group</a>
            </footer> 
        </div>
        
        <!-- load JS files -->
        <script src="js/jquery-1.11.3.min.js"></script>         <!-- jQuery (https://jquery.com/download/) -->
        <script>     
       
            $(document).ready(function(){
                
                // Update the current year in copyright
                $('.tm-current-year').text(new Date().getFullYear());

            });

        </script>             

</body>
</html>