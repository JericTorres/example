<?php
session_start();

// Manually include PHPMailer files
require_once '../PHP/PHPMailer/src/Exception.php';
require_once '../PHP/PHPMailer/src/PHPMailer.php';
require_once '../PHP/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lis";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['signup'])) {
    // Sanitize input
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $grade_level = mysqli_real_escape_string($conn, $_POST['grade_level']);
    $strand = isset($_POST['strand']) ? mysqli_real_escape_string($conn, $_POST['strand']) : null;

    // Check for duplicate email
    $check_sql = "SELECT * FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Email is already registered!";
    } else {
        // Insert into database
        $insert_sql = "INSERT INTO users (email, full_name, phone, dob, grade_level, strand)
                       VALUES ('$email', '$full_name', '$phone', '$dob', '$grade_level', " . ($strand ? "'$strand'" : "NULL") . ")";

        if ($conn->query($insert_sql) === TRUE) {
            // Email configuration
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server to Gmail
                $mail->SMTPAuth   = true;
                $mail->Username   = 'jerictorrjerictorres456@gmail.com';  // Your Gmail address
                $mail->Password   = 'jask pmqc cvoy txzr'; // Use your app password if 2FA enabled
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Send thank-you email to user
                $mail->setFrom('jerictorrjerictorres456@gmail.com', 'Lakeview Integrated School');
                $mail->addAddress($email, $full_name);

                $mail->isHTML(true);
                $mail->Subject = 'Thank You for Registering - Lakeview Integrated School';
                $mail->Body = "
                    <h1>Thank You for Registering!</h1>
                    <p>Hello {$full_name},</p>
                    <p>Thank you for registering at <strong>Lakeview Integrated School</strong>.</p>
                    <p>Your registration form has been successfully received. Our admissions team will review your submission and get back to you within <strong>48 hours</strong>.</p>
                    <p>Please check your email regularly for updates or further instructions.</p>
                    <br>
                    <p>Best regards,<br><strong>Lakeview Integrated School Admissions Team</strong></p>";

                // Send the email
                if (!$mail->send()) {
                    echo 'Mailer Error: ' . $mail->ErrorInfo; // Show PHPMailer's error if sending fails
                } else {
                    // Send notification email to admin
                    $mail->clearAddresses();
                    $mail->addAddress('Joycedeguzman519@gmail.com', 'Admin'); // Replace with real admin email
                    $mail->Subject = 'New Student Registration Submitted';
                    $mail->Body = "
                        <h1>New Registration Received</h1>
                        <p>A new registration has been submitted:</p>
                        <ul>
                            <li><strong>Full Name:</strong> {$full_name}</li>
                            <li><strong>Email:</strong> {$email}</li>
                            <li><strong>Phone:</strong> {$phone}</li>
                            <li><strong>Date of Birth:</strong> {$dob}</li>
                            <li><strong>Grade Level:</strong> {$grade_level}</li>" . 
                            ($strand ? "<li><strong>Strand:</strong> {$strand}</li>" : "") . "
                        </ul>
                        <p>Please log in to the admin panel to review the submission.</p>";

                    if (!$mail->send()) {
                        echo 'Mailer Error: ' . $mail->ErrorInfo; // Show PHPMailer's error if sending fails
                    }
                }

                // Redirect to login.html with success parameter
                header("Location: ../HTML/login.html?success=true");
                exit();
            } catch (Exception $e) {
                echo "Email sending failed: " . $mail->ErrorInfo;
            }
        } else {
            echo "Database Error: " . $conn->error;
        }
    }
}

$conn->close();
?>
