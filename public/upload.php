<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['images'])) {
        $file = $_FILES['images'];
        
        // Check for errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo "Upload error: " . $file['error'];
            exit;
        }
        
        // Check file size
        if ($file['size'] === 0) {
            echo "File size is 0. Possible causes:";
            echo "<br>- PHP upload limits too low";
            echo "<br>- Disk space full";
            echo "<br>- Permission issues";
            exit;
        }
        
        // Move the file to desired location
        $targetPath = "uploads/" . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo "File uploaded successfully!";
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "No file uploaded or form not properly configured.";
    }
}
?>