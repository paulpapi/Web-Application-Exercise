<?php
require_once __DIR__ . '/../config/db.php';
$isLoggedIn =FALSE;
session_start();
if($_SESSION["username"]){
  $isLoggedIn = TRUE;
  //echo "<p>Welcome back " . $_SESSION["username"];
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

	 </head>

  <body>

    <div class="container">
      <header class="tm-site-header">
        <h1 class="tm-site-name">PL</h1>
        <p class="tm-site-description">Your Online Public Listings Website</p><br/><br/>

        <form class="custom-form hero-form" action="search.php" method="get" role="form">
          <div class="row">
              <div class="col-lg-9 col-md-6 col-12">
                  <div class="input-group align-items-center">
                      <label for="listing-name">Listings </label>

                      <input type="text" name="listing-name" id="listing-name" class="form-control" placeholder="Phones, Cars..." required>
                  </div>
              </div>          
              <div class="col-lg-2 col-md-6 col-12">
                  <button type="submit" class="form-control tm-btn tm-btn-blue">Search</button>
              </div>
              <input type="hidden" name="min" value="0"/>
              <input type="hidden" name="max" value="1000000000"/>
              <input type="hidden" name="cat" value="*"/>
          </div>
        </form>

        <nav class="navbar navbar-expand-md tm-main-nav-container">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#tmMainNav" aria-controls="tmMainNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
          </button>

          <div class="collapse navbar-collapse tm-main-nav" id="tmMainNav">
            <ul class="nav nav-fill tm-main-nav-ul">
              <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
              <?php if($isLoggedIn){
                  echo <<<HTML
                  <li class="nav-item"><a class="nav-link" href="addListing.php">Add Listing</a></li>
                  HTML;
              }
              ?>
              <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
              <?php if(!$isLoggedIn) echo <<<HTML
              <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
              HTML;
              else echo <<<HTML
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                HTML;
              ?>
            </ul>
          </div>
        </nav>

      </header>

      <div class="tm-main-content">
        <section class="tm-margin-b-l">
          <header>

            <h2 class="tm-main-title"><t/>Welcome to PL <?php if($isLoggedIn) echo $_SESSION['username'];?></h2>
          </header>

          <p>This an Online platform where anyone can post listing and connect with others.</p>

          <div class="tm-gallery">
            <div class="row">
        <?php
            $itemsPerPage = 8;
            $i = 0;
            
            $offset = $_GET['offset'] ?? 0;
            // $offset = $_GET["offset"] ?? 0;
            $sql = "SELECT * FROM products ORDER BY time DESC LIMIT " . $itemsPerPage . " OFFSET " . $offset . ";";
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

          <nav class="tm-gallery-nav">
            <ul class="nav justify-content-center">
              <?php
                $sql = "SELECT COUNT(*) AS num FROM products";
                $result =  mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $total = $row["num"];
                $j = 1;
                while($j <= floor($total/$itemsPerPage) + 1){
                  $offset = ($j-1)*$itemsPerPage;
                  if($offset == $_GET['offset']){
                    echo <<<HTML
                    <li class="nav-item"><a class="nav-link active" href="index.php?offset=$offset">$j</a></li>
                    HTML;
                  }else {
                    echo <<<HTML
                    <li class="nav-item"><a class="nav-link" href="index.php?offset=$offset">$j</a></li>
                    HTML;
                  }
                  $j++;
                }
              ?>
            </ul>
          </nav>
        </section>

        <section class="media tm-highlight tm-highlight-w-icon">

          <div class="tm-highlight-icon">
            <i class="fa tm-fa-6x fa-meetup"></i>
          </div>

          <div class="media-body">
            <header>
              <h2>Need Help?</h2>
            </header>
            <p class="tm-margin-b">Curabitur magna tellus, condimentum vitae consectetur id, elementum sit amet erat. Phasellus arcu leo, sagittis fringilla nisi et, pulvinar vestibulum mi. Maecenas mollis ullamcorper est at dignissim.</p>
            <a href="" class="tm-white-bordered-btn">Live Chat</a>
          </div>
        </section>
      </div>

      <footer>
          Copyright &copy; <span class="tm-current-year">2018</span> Nobody.

          - Designed by <a href="https://www.facebook.com/" target="_parent">The Group</a>
      </footer>
      
    </div>

    <!-- load JS files -->
    <script src="js/jquery-1.11.3.min.js"></script>         <!-- jQuery (https://jquery.com/download/) -->
    <!--
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        -->
        <script>

          $(document).ready(function(){

              // Update the current year in copyright
              $('.tm-current-year').text(new Date().getFullYear());

          });

        </script>

  </body>
</html>
