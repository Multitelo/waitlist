<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';

include 'cone.php';

// Function to send a welcome email
function sendWelcomeEmail($email, $username, $refid) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'mail.qwesty.site';
        $mail->SMTPAuth = true;
        $mail->Username = 'team@qwesty.site';
        $mail->Password = 'Qwestyteam2023.';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

         // Use a custom SSL certificate if available
        //  $mail->SMTPOptions = [
        //     'ssl' => [
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true,
        //     ],
        // ];

        // Sender and recipient settings
        $mail->setFrom('team@qwesty.site','SOLVETY (formerly Qwesty)');
        $mail->addAddress($email, $username);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'She\'s back!!';
        $mail->Body = '<!DOCTYPE html>
        <html lang="en">
          <head>
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
            <link
              href="https://fonts.googleapis.com/css2?family=Outfit&family=Space+Grotesk&display=swap"
              rel="stylesheet"
            />
          </head>
          <body style="width: 80vw; margin: 0 auto; font-family: Outfit, sans-serif;">
          <p>          
          How long was it?<br>
          Mathematically, it was a month.<br>
          Emotionally, it feels forever.<br><br>
          
          I have been on a retreat since April and yes, I told you that we got an email marketer. But the truth is; no one can understand you better than someone in the same shoe as you.<br><br>
          
          
          So just like you, I have always had the urge and interested in sharing my opinions with people, brands or whatever entity as far as it would improve them.<br><br>
          
          Then, I stumbled upon some websites that even promise to pay for such gesture. Interesting!<br><br>
          
          But imagine what that cost me, my sanity.<br>
          Taking exams is stressful but we all do it for our greater good.
           Now imagine feeling like you\'re taking exams. At worst, you don\'t feel appreciated nor cared for.<br><br>
          
          This is the exact reason why we decided to build this platform but this email is\'t for those stories that touches the heart.<br><br>
          
          I just want to scream that I\'m back to you! And to let you know that you will be hearing from us as usual.<br><br>
          So stay tuned to your email 👀.<br><br><br>
          
          <b>Skyrose</b><br>
          <i>Founder and Product Manager</i>
          </p>
          </body>
        </html>';

        // Send the email
        $mail->send();

        echo "Welcome email sent successfully to $email<br>";
    } catch (Exception $e) {
        echo "Error sending email to $email: {$mail->ErrorInfo}<br>";
    }
}

// Fetch all emails and usernames where welcome_email_sent = 0
$sqlFetchUnsentEmails = "SELECT email, username, refid FROM parti WHERE welcome_email_sent = 0";
$resultFetchUnsentEmails = mysqli_query($conn, $sqlFetchUnsentEmails);

if (!$resultFetchUnsentEmails) {
    echo "Error fetching unsent emails: " . mysqli_error($conn);
    exit();
}

// Loop through the unsent emails and send welcome emails
while ($row = mysqli_fetch_assoc($resultFetchUnsentEmails)) {
    $email = $row['email'];
    $username = $row['username'];
    $refid = $row['refid'];

    // Send welcome email
    sendWelcomeEmail($email, $username, $refid);

    // Update welcome_email_sent to 1
    $sqlUpdateWelcomeEmailStatus = "UPDATE parti SET welcome_email_sent = 1 WHERE email = '$email'";
    $resultUpdateWelcomeEmailStatus = mysqli_query($conn, $sqlUpdateWelcomeEmailStatus);

    if (!$resultUpdateWelcomeEmailStatus) {
        echo "Error updating welcome email status for $email: " . mysqli_error($conn);
        // You might choose to exit here or log the error, depending on your requirements
    }
}

// Close the database connection
mysqli_close($conn);

echo "Script executed successfully.";
?>
