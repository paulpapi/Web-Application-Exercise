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
                <p class="tm-site-description">Your Online Public Listings Website</p>
                
                <nav class="navbar navbar-expand-md tm-main-nav-container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#tmMainNav" aria-controls="tmMainNav" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse tm-main-nav" id="tmMainNav">
                        <ul class="nav nav-fill tm-main-nav-ul">
                            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                            <!-- <li class="nav-item"><a class="nav-link" href="#">Catalogs</a></li> -->
                            <!-- <li class="nav-item"><a class="nav-link" href="#">Awards</a></li> -->
                            <li class="nav-item"><a class="nav-link active" href="contact.php">Contact Us</a></li>
                        </ul>
                    </div>    
                </nav>
                
            </header>
            
            <div class="tm-main-content">
                <section class="row tm-margin-b-l">
                    <div class="col-12">
                        <h2 class="tm-blue-text tm-margin-b-p">Contact Us</h2>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-md-0 mb-5 tm-overflow-auto">         
                        <div class="mr-lg-5">
                            <!-- contact form -->
                            <form action="#" method="post" class="tm-contact-form">
                                <div class="form-group">
                                    <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Name"  required/>
                                </div>
                                <div class="form-group">                                                        
                                    <input type="email" id="contact_email" name="contact_email" class="form-control" placeholder="Email"  required/>
                                </div>
                                <div class="form-group">
                                    <textarea id="contact_message" name="contact_message" class="form-control" rows="8" placeholder="Message" required></textarea>
                                </div>
                                <button type="submit" class="tm-btn tm-btn-blue float-right">Submit</button>
                            </form>                          
                        </div>                                       
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <p class="tm-margin-b-p">Morbi auctor euismod dictum. Morbi eu nibh in nulla interdum placerat. Donec pellentesque est at sem aliquam hendrerit.</p>
                        <address>
                            <span class="tm-blue-text">Mailing Address</span><br>
                            We don't have mail bro.<br>
                            Who uses mail in 2025 anyway.<br><br>
                            <div class="tm-blue-text">          
                                Email: <a class="tm-blue-text" href="mailto:info@company.com">info@thegroup.com</a
                                ><br>
                                Tel: <a class="tm-blue-text" href="tel:+251949023487">0949023487</a><br>
                                Fax: <a class="tm-blue-text" href="tel:+251934332233">0934332233</a><br>    
                            </div>                            
                        </address>
                        
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

</body>
</html>