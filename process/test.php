<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';

include 'cone.php';

// Function to send a welcome email
function sendWelcomeEmail($email, $username, $body) {
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
        $mail->Subject = 'Welcome to Our Website';
        $mail->Body = $body;

        // Send the email
        $mail->send();

        // echo "Welcome email sent successfully to $email<br>";
    } catch (Exception $e) {
        echo "Error sending email to $email: {$mail->ErrorInfo}<br>";
    }
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Check if process.php is being accessed directly
    if(basename(__FILE__) === basename($_SERVER["SCRIPT_FILENAME"])) {
        // Redirect the user to another page or display an error message
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}

// Function to generate a unique referral code
function generateReferralCode() {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
}

// Function to check if the referral code already exists in the database
function isReferralCodeUnique($code, $conn) {
    $query = "SELECT * FROM parti WHERE refid = '$code'";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) === 0; // If there are no rows, the code is unique
}

// Function to log in the user by setting a session (simplified example)
if (!function_exists('login')) {
    function login($email, $sessionKey) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }
        $_SESSION[$sessionKey] = $email; // You may want to store more information in the session
        $_SESSION['last_activity'] = time(); // Update last activity timestamp
    }
}

// Function to update the referral count for the referrer
function updateReferralCount($referrerCode, $conn) {
    if (!empty($referrerCode)) {
        $query = "UPDATE parti SET refcount = refcount + 1 WHERE refid = '$referrerCode'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "Error updating referral count: " . mysqli_error($conn);
        }
    }
}

// Start the session (if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Researcher Form Handling
    if (isset($_POST["bemail"]) && isset($_POST["bizname"])) {
        $email = mysqli_real_escape_string($conn, $_POST["bemail"]);
        $username = mysqli_real_escape_string($conn, $_POST["bizname"]);

        // Validate the data (you may want to add more validation)
        if (empty($email) || empty($username)) {
            echo "Please provide both email and username.";
        } else {
            // Check if email already exists in another table
            $queryOtherTable = "SELECT * FROM parti WHERE email = '$email'";
            $resultOtherTable = mysqli_query($conn, $queryOtherTable);

            if (mysqli_num_rows($resultOtherTable) > 0) {
                echo "<script>
                alert('User already part of the waitlist as a Participant.');
                window.location.href='../index.php';
                </script>";
                exit();
            }

            // Check if email already exists
            $queryEmail = "SELECT * FROM resea WHERE bemail = '$email'";
            $resultEmail = mysqli_query($conn, $queryEmail);
            if (mysqli_num_rows($resultEmail) > 0) {
                // Log in the user if the email already exists (simplified example)
                login($email, 'bemail');
                echo "<script>
                    alert('Already part of the waitlist. Welcome!');
                    window.location.href='../homer.php';
                </script>";
                exit();
            }

            // Check if username is already taken
            $queryUsername = "SELECT * FROM resea WHERE bizname = '$username'";
            $resultUsername = mysqli_query($conn, $queryUsername);
            if (mysqli_num_rows($resultUsername) > 0) {
                echo "Username already taken. Please choose a different one.";
                exit();
            }

            // Save the new entry to the database
            $query = "INSERT INTO resea (bemail, bizname) VALUES ('$email', '$username')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                login($email, 'bemail');
                // Display the results
                echo "
                    <script>
                        alert('You have Successfully joined the Waitlist.');
                        window.location.href='../homer.php';
                    </script>";
                    $reseaBody = '<!DOCTYPE html>
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
                            background-color: #5650bc;
                            height: fit-content;
                            padding: 2%;
                            border-radius: 8px;
                            color: azure;
                            font-family: \'Outfit\', sans-serif;
                          "
                        >
                          <p>Great, ' . $username . '!</p>
            
                          <p>
                            You\'re now part of Qwesty waiting list for researchers! As you already
                            know, you\'re not going to stand under the Xmas cold waiting for us to
                            finish rambling our head on the best way to help you.
                          </p>
                          <p>So in the meantime, let\'s have some fun with data.</p>
                          <img
                            src="https://qwesty.site/images/Artboard%203@2x-100.jpg"
                            alt="Do you know that bad data cost the US economy $3 trillion annually and affects 26% of company revenues?"
                            style="width: 100%; height: 100%"
                          />
            
                          <p>
                            Which is why, we\'ll keep you updated on our progress so that you wont
                            be one of the victims of that statistics but even you already are, we are
                            looking forward to helping you as well.
                          </p>
                        </div>
                      </body>
                    </html>';;
                    sendWelcomeEmail($email, $username, $reseaBody);

            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }

    // Participant Form Handling
    elseif (isset($_POST["email"]) && isset($_POST["username"])) {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $referrerCode = mysqli_real_escape_string($conn, $_POST["referrer"]);

        // Validate the data (you may want to add more validation)
        if (empty($email) || empty($username)) {
            echo "Please provide both email and username.";
        } else {
            // Check if email already exists in another table
            $queryOtherTable = "SELECT * FROM resea WHERE bemail = '$email'";
            $resultOtherTable = mysqli_query($conn, $queryOtherTable);

            if (mysqli_num_rows($resultOtherTable) > 0) {
                echo "<script>
                alert('User already part of the waitlist as a Researcher.');
                window.location.href='../index.php';
                </script>";
                exit();
            }

            // Check if email already exists
            $queryEmail = "SELECT * FROM parti WHERE email = '$email'";
            $resultEmail = mysqli_query($conn, $queryEmail);
            if (mysqli_num_rows($resultEmail) > 0) {
                // Log in the user if the email already exists (simplified example)
                login($email, 'email');
                echo "<script>
                    alert('Already part of the waitlist. Welcome!');
                    window.location.href='../home.php';
                </script>";

                // You can add more details here

                // Update referral count for the referrer
                updateReferralCount($referrerCode, $conn);
                echo "Referral count updated for referrer!<br>";

                exit();
            }

            // Check if username is already taken
            $queryUsername = "SELECT * FROM parti WHERE username = '$username'";
            $resultUsername = mysqli_query($conn, $queryUsername);
            if (mysqli_num_rows($resultUsername) > 0) {
                echo "Username already taken. Please choose a different one.";
                exit();
            }

            // Check if the referrer code is valid
            if (!empty($referrerCode)) {
                $query = "SELECT * FROM parti WHERE refid = '$referrerCode'";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) === 0) {
                    echo "Invalid referrer code.";
                    exit();
                }
            }

            // Generate a unique referral code
            do {
                $referralCode = generateReferralCode();
            } while (!isReferralCodeUnique($referralCode, $conn));

            // Save the new entry to the database
            $query = "INSERT INTO parti (email, username, refid, referer) VALUES ('$email', '$username', '$referralCode', '$referrerCode')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                login($email, 'email');
                // Display the results
                echo "
                    <script>
                        alert('You have Successfully joined the Waitlist.');
                        window.location.href='../home.php';
                    </script>";

                updateReferralCount($referrerCode, $conn);
                echo "Referral count updated for referrer!<br>";
                $partiBody = '<!DOCTYPE html>
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
                        background-color: #5650bc;
                        height: fit-content;
                        padding: 2%;
                        border-radius: 8px;
                        color: azure;
                        font-family: \'Outfit\', sans-serif;
                      "
                    >
                      <p>You found us, ' . $username . '!!</p>
                
                      <p>
                        You have joined Qwesty waiting list and you will be one of the first to
                        get a glimpse into the future of data collection which we\'re working on.
                      </p>
                
                      <p>
                        For now, enjoy the rest your day as we\'re going to be checking up on you
                        in every two weeks with updates of whatever we have for you.
                      </p>
                
                      <p>
                        Get your invite link to invite friends to wait with you 😌, from
                        <a href="https://qwesty.site">here.</a>
                      </p>
                      
                    </div>
                  </body>
                </html>
                ';
                sendWelcomeEmail($email, $username, $partiBody);
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Close the database connection
if ($conn) {
    mysqli_close($conn);
}
?>