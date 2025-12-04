<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_info = isset($_POST['product_info']) ? htmlspecialchars($_POST['product_info']) : '';
    $product_image = isset($_POST['product_image']) ? $_POST['product_image'] : '';
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $contact = isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';

    $to = "abhaypal@3sptechmind.com";
    $subject = "New Product Inquiry: $product_info";

    $message = "
        <h2>New Product Inquiry</h2>
        <p><strong>Product:</strong> $product_info</p>
        <p><img src='$product_image' width='300' alt='Product Image' /></p>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Contact Number:</strong> $contact</p>
        <p><strong>Email:</strong> $email</p>
    ";

    $fromEmail = 'abpal545@gmail.com'; 

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Website Inquiry <$fromEmail>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        header("Location: shop-details.php?success=1");
        exit();
    } else {
        header("Location: shop-details.php?success=0");
        exit();
    }

    // For debugging (optional, uncomment to test)
    // file_put_contents('mail_log.txt', "Mail sent to: $to\nSubject: $subject\nHeaders: $headers\nMessage: $message\n\n", FILE_APPEND);
} else {
    header("Location: shop-details.php");
    exit();
}
?>
