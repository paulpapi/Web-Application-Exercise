<?php
require_once __DIR__ . '/../config/db.php';
$isLoggedIn =FALSE;

session_start();
if($_SESSION["username"]){
  //echo "<p>Welcome back " . $_SESSION["username"];
  $isLoggedIn = TRUE;
}else{
    header("Location: index.php");
}
$errors = [];
$success = false;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate and sanitize inputs
    $name = trim($_POST["name"] ?? '');
    $price = trim($_POST["price"] ?? '');
    $category = trim($_POST["category"] ?? '');
    $sdesc = trim($_POST["sdesc"] ?? '');
    $ldesc = trim($_POST["ldesc"] ?? '');
    $timestamp = time();
    
    // Validate name
    if(empty($name)) {
        $errors['name'] = "Product name is required";
    } elseif(strlen($name) > 50) {
        $errors['name'] = "Product name must be 50 characters or less";
    }
    
    // Validate price
    if(empty($price)) {
        $errors['price'] = "Price is required";
    } elseif(!is_numeric($price) || $price <= 0) {
        $errors['price'] = "Price must be a positive number";
    }
    
    // Validate category
    $allowedCategories = ['electronics', 'books', 'clothing', 'home'];
    if(empty($category) || !in_array($category, $allowedCategories)) {
        $errors['category'] = "Please select a valid category";
    }
    
    // Validate short description
    if(empty($sdesc)) {
        $errors['sdesc'] = "Short description is required";
    } elseif(strlen($sdesc) > 200) {
        $errors['sdesc'] = "Short description must be 200 characters or less";
    }
    
    // Validate long description
    if(!empty($ldesc) && strlen($ldesc) > 500) {
        $errors['ldesc'] = "Detailed information must be 500 characters or less";
    }
    
    // Validate images
    $uploadedFiles = [];
    $mainImage = '';
    $imageErrors = [];
    
    if(empty($_FILES['images']['name'][0])) {
        $errors['images'] = "At least one image is required";
    } else {
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        $fileCount = count($_FILES['images']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['images']['tmp_name'][$i];
                $fileType = mime_content_type($fileTmpPath);
                $fileSize = $_FILES['images']['size'][$i];
                
                if (!in_array($fileType, $allowedTypes)) {
                    $imageErrors[] = "Only JPG, PNG, and GIF images are allowed";
                    break;
                }
                
                if ($fileSize > $maxFileSize) {
                    $imageErrors[] = "Each image must be less than 2MB";
                    break;
                }
            } elseif ($_FILES['images']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                $imageErrors[] = "There was an error uploading one of your files";
                break;
            }
        }
        
        if(!empty($imageErrors)) {
            $errors['images'] = implode('<br>', $imageErrors);
        }
    }
    
    // Only proceed if no errors
    if(empty($errors)) {
        // Process file uploads
        $uploadDir = 'uploads/' . $category . "/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['images']['tmp_name'][$i];
                $random = bin2hex(random_bytes(5));
                $extension = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                $fileName = $timestamp . '_' . $random . '.' . $extension;
                $destination = $uploadDir . $fileName;
                
                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $uploadedFiles[] = $fileName;
                    if ($i === 0) {
                        $mainImage = $fileName;
                    }
                }
            }
        }
        
        // Insert into database
        $ownerName = $_SESSION['username'];
        $imagesString = implode(',', $uploadedFiles);
        
        $sql = "INSERT INTO products(name, price, sdesc, ldesc, ownerName, category, mimage, aimage) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insert = mysqli_prepare($conn, $sql);
        $insert->bind_param("sdssssss", $name, $price, $sdesc, $ldesc, $ownerName, $category, $mainImage, $imagesString);
        
        if($insert->execute()) {
            $success = true;
        } else {
            $errors['database'] = "Error inserting product: " . $conn->error;
        }
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
    <link rel="stylesheet" href="css/add-listing.css">
    <style>
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .image-preview {
            position: relative;
            width: 100px;
            height: 100px;
        }
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .remove-btn {
            position: absolute;
            top: 0;
            right: 0;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .file-input-group {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #add-more-btn {
            margin-bottom: 20px;
        }
        .error {
            color: red;
            font-size: 0.9em;
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
    </style>
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
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="tm-main-content">
            <section class="tm-margin-b-l">
                <header>
                    <h2 class="tm-main-title">Add New Product</h2>
                </header>
            </section>

            <div>
                <?php if($success): ?>
                    <div class="success-message">
                        Product added successfully!
                    </div>
                <?php elseif(!empty($errors['database'])): ?>
                    <div class="error">
                        <?= htmlspecialchars($errors['database']) ?>
                    </div>
                <?php endif; ?>

                <form action="addListing.php" method="POST" enctype="multipart/form-data" id="product-form">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required />
                    <?php if(!empty($errors['name'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['name']) ?></div>
                    <?php endif; ?>

                    <label for="price">Price ($)</label>
                    <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required />
                    <?php if(!empty($errors['price'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['price']) ?></div>
                    <?php endif; ?>

                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="electronics" <?= (isset($_POST['category']) && $_POST['category'] === 'electronics') ? 'selected' : '' ?>>Electronics</option>
                        <option value="books" <?= (isset($_POST['category']) && $_POST['category'] === 'books') ? 'selected' : '' ?>>Books</option>
                        <option value="clothing" <?= (isset($_POST['category']) && $_POST['category'] === 'clothing') ? 'selected' : '' ?>>Clothing</option>
                        <option value="home" <?= (isset($_POST['category']) && $_POST['category'] === 'home') ? 'selected' : '' ?>>Home</option>
                    </select>
                    <?php if(!empty($errors['category'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['category']) ?></div>
                    <?php endif; ?>

                    <label for="sdesc">Short Description</label>
                    <textarea id="sdesc" name="sdesc" rows="3" required><?= htmlspecialchars($_POST['sdesc'] ?? '') ?></textarea>
                    <?php if(!empty($errors['sdesc'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['sdesc']) ?></div>
                    <?php endif; ?>

                    <label for="ldesc">Detailed Information</label>
                    <textarea id="ldesc" name="ldesc" rows="5"><?= htmlspecialchars($_POST['ldesc'] ?? '') ?></textarea>
                    <?php if(!empty($errors['ldesc'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['ldesc']) ?></div>
                    <?php endif; ?>
                    <br/>

                    <label>Product Images</label>
                    <div id="image-inputs-container">
                        <div class="file-input-group">
                            <input type="file" name="images[]" class="image-input" accept="image/*" required>
                            <button type="button" class="remove-btn" onclick="removeImageInput(this)">×</button>
                        </div>
                    </div>
                    <?php if(!empty($errors['images'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['images']) ?></div>
                    <?php endif; ?>
                    <button type="button" id="add-more-btn" class="btn btn-secondary">Add Another Image</button>

                    <div id="preview-container" class="image-preview-container"></div>

                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>

            <footer>
                Copyright &copy; <span class="tm-current-year">2018</span> Nobody.
                - Designed by <a href="https://www.facebook.com/" target="_parent">The Group</a>
            </footer>
        </div>
    </div>

    <!-- load JS files -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.tm-current-year').text(new Date().getFullYear());
        });
        
        // Add more image inputs
        document.getElementById('add-more-btn').addEventListener('click', function() {
            const container = document.getElementById('image-inputs-container');
            const newInputGroup = document.createElement('div');
            newInputGroup.className = 'file-input-group';
            newInputGroup.innerHTML = `
                <input type="file" name="images[]" class="image-input" accept="image/*" required>
                <button type="button" class="remove-btn" onclick="removeImageInput(this)">×</button>
            `;
            container.appendChild(newInputGroup);
        });

        // Remove image input
        function removeImageInput(button) {
            const inputGroups = document.querySelectorAll('.file-input-group');
            if (inputGroups.length > 1) {
                button.parentElement.remove();
                updateImagePreviews();
            } else {
                alert('You need at least one image.');
            }
        }

        // Preview images
        function updateImagePreviews() {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = '';
            
            const files = [];
            document.querySelectorAll('.image-input').forEach(input => {
                if (input.files[0]) {
                    files.push(input.files[0]);
                }
            });
            
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview';
                    previewDiv.innerHTML = `
                        <img src="${event.target.result}" alt="Preview">
                    `;
                    previewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            });
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('image-input')) {
                updateImagePreviews();
            }
        });

        // Form validation
        document.getElementById('product-form').addEventListener('submit', function(e) {
            let isValid = true;
            const errors = {};
            
            // Clear previous error highlights
            document.querySelectorAll('.error-highlight').forEach(el => {
                el.classList.remove('error-highlight');
            });
            
            // Validate name
            const name = document.getElementById('name').value.trim();
            if (!name) {
                errors.name = 'Product name is required';
                isValid = false;
                document.getElementById('name').classList.add('error-highlight');
            } else if (name.length > 50) {
                errors.name = 'Product name must be 50 characters or less';
                isValid = false;
                document.getElementById('name').classList.add('error-highlight');
            }
            
            // Validate price
            const price = parseFloat(document.getElementById('price').value);
            if (isNaN(price)) {
                errors.price = 'Price must be a number';
                isValid = false;
                document.getElementById('price').classList.add('error-highlight');
            } else if (price <= 0) {
                errors.price = 'Price must be greater than 0';
                isValid = false;
                document.getElementById('price').classList.add('error-highlight');
            }
            
            // Validate category
            const category = document.getElementById('category').value;
            if (!category) {
                errors.category = 'Please select a category';
                isValid = false;
                document.getElementById('category').classList.add('error-highlight');
            }
            
            // Validate short description
            const sdesc = document.getElementById('sdesc').value.trim();
            if (!sdesc) {
                errors.sdesc = 'Short description is required';
                isValid = false;
                document.getElementById('sdesc').classList.add('error-highlight');
            } else if (sdesc.length > 200) {
                errors.sdesc = 'Short description must be 200 characters or less';
                isValid = false;
                document.getElementById('sdesc').classList.add('error-highlight');
            }
            
            // Validate long description
            const ldesc = document.getElementById('ldesc').value.trim();
            if (ldesc.length > 500) {
                errors.ldesc = 'Detailed information must be 500 characters or less';
                isValid = false;
                document.getElementById('ldesc').classList.add('error-highlight');
            }
            
            // Validate images
            const imageInputs = document.querySelectorAll('.image-input');
            let hasValidImage = false;
            for (let input of imageInputs) {
                if (input.files[0]) {
                    hasValidImage = true;
                    // Check file type
                    const fileType = input.files[0].type;
                    if (!fileType.match('image.*')) {
                        errors.images = 'Only image files are allowed';
                        isValid = false;
                        input.classList.add('error-highlight');
                    }
                    // Check file size (2MB max)
                    if (input.files[0].size > 2 * 1024 * 1024) {
                        errors.images = 'Each image must be less than 2MB';
                        isValid = false;
                        input.classList.add('error-highlight');
                    }
                    break;
                }
            }
            if (!hasValidImage) {
                errors.images = 'At least one image is required';
                isValid = false;
                document.querySelector('.image-input').classList.add('error-highlight');
            }
            
            if (!isValid) {
                e.preventDefault();
                // Display errors to user
                for (const [field, message] of Object.entries(errors)) {
                    const errorElement = document.createElement('div');
                    errorElement.className = 'error';
                    errorElement.textContent = message;
                    
                    const existingError = document.querySelector(`#${field}`).nextElementSibling;
                    if (existingError && existingError.className === 'error') {
                        existingError.textContent = message;
                    } else {
                        document.querySelector(`#${field}`).after(errorElement);
                    }
                }
            }
        });
    </script>
</body>
</html>