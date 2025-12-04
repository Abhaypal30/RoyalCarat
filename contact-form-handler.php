<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $contact = htmlspecialchars(trim($_POST["contact"])); 
    $message = htmlspecialchars(trim($_POST["message"]));

    
    $to = "abhaypal@3sptechmind.com"; 
    $subject = "New Contact Form Submission from Royal Carat";
    $body = "You have received a new message from Royal Carat's Contact Form:\n\n";
    $body .= "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Contact Number: $contact\n";  
    $body .= "Message:\n$message\n";

    $headers = "From: Royal Carat <no-reply@royalcarat.com>\r\n";
    $headers .= "Reply-To: $email\r\n";

    
    if (mail($to, $subject, $body, $headers)) {
      
        header("Location: thank-you.php");
        exit();
    } else {
        echo "Sorry, something went wrong. Please try again later.";
    }
} else {
  
    header("Location: index.php");
    exit();
}
?>
<?php
include 'db.php';  // Make sure this file sets up $conn for DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $contact = htmlspecialchars(trim($_POST["contact"])); 
    $message = htmlspecialchars(trim($_POST["message"]));

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO inquiry (name, email, contact, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $contact, $message);

    if ($stmt->execute()) {
        echo "Inquiry saved successfully!";
    } else {
        echo "Error saving inquiry: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Invalid request method.";
}
?>

