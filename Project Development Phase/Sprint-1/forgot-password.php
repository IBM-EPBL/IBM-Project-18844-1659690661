<?php

include 'config.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

error_reporting(0);

if (isset($_SESSION["user_id"])) {
  header("Location: welcome.php");
}
if (isset($_POST["resetPassword"])) {
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($check_email) > 0) {
    $data = mysqli_fetch_assoc($check_email);
    $to = $email;
    $subject = "Reset Password - Scan My Nutri";
    $message = "
    <html>
    <head>
    <title>{$subject}</title>
    </head>
    <body>
    <p><strong>Dear {$data['full_name']},</strong></p>
    <p>Forgot Password? Not a problem. Click below link to reset your password.</p>
    <p><a href='{$base_url}reset-password.php?token={$data['token']}'>Reset Password</a></p>
    </body>
    </html>
    ";
    $mail = new PHPMailer(true);

    try {
     
      $mail->SMTPDebug = 0;                      
      $mail->isSMTP();                                           
      $mail->Host       = $smtp['host'];                    
      $mail->SMTPAuth   = true;                                  
      $mail->Username   = $smtp['user'];                    
      $mail->Password   = $smtp['pass'];                               
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
      $mail->Port       = $smtp['port'];                              
      $mail->setFrom($my_email);
      $mail->addAddress($email, $data['full_name']);     
      $mail->isHTML(true);                                 
      $mail->Subject = $subject;
      $mail->Body    = $message;
      $mail->send();
      echo "<script>alert('We have sent a reset password link to your email - {$email}.');</script>";
    } catch (Exception $e) {
      echo "<script>alert('Mail not sent. Please try again.');</script>";
    }
  } else {
    echo "<script>alert('Email not found.');</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>Scan My Nutri</title>
</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="" method="post" class="sign-in-form">
          <h2 class="title">Reset Password</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Email Address" name="email" value="<?php echo $_POST['email']; ?>" required />
          </div>
          <input type="submit" value="Send Verification Link" name="resetPassword" class="btn solid" />
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>Forgot Password ?</h3>
          
        </div>
        <img src="img/img1.png" class="image" alt="" />
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <script src="app.js"></script>
</body>

</html>