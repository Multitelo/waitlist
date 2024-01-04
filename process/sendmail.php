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
        $mail->setFrom('team@qwesty.site','Qwesty');
        $mail->addAddress($email, $username);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Season\'s Greetings';
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
          <body style="width: 80vw; margin: 0 auto">
            <div
              style="
                width: 100%;
                min-width: 300px;
                max-width: 720px;
                margin: 5% auto;
                background-color: #7F56D9;
                height: fit-content;
                padding: 2%;
                border-radius: 8px;
                color: azure;
                font-family: Outfit, sans-serif;
              "
            >
              <p>Merry Xmas, <b>' . $username . '!</b></p>
              <img src="https://qwesty.site/images/xmasgt.jpg" alt="Qwesty Message" srcset="" />
              <p>
                Get your invite link to invite friends to wait with you 😌, from
                <a href="https://qwesty.site">here.</a>
                <br><br>
                or Copy the link below<br><br>https://qwesty.site?' . $refid . '.
              </p>
                    </div>
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
