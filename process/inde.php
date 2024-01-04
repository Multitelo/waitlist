<?php

include 'cone.php';

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
    function login($email) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }
        $_SESSION['email'] = $email; // You may want to store more information in the session
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
    
    // Get data from the form
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

        // Check if email already exists in the current table
        $queryEmail = "SELECT * FROM parti WHERE email = '$email'";
        $resultEmail = mysqli_query($conn, $queryEmail);

        if (mysqli_num_rows($resultEmail) > 0) {
            // Log in the user if the email already exists (simplified example)
            login($email);
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
            login($email);
            // Display the results
            echo "
                <script>
                    alert('You have Successfully joined the Waitlist.');
                    window.location.href='../home.php';
                </script>";

            updateReferralCount($referrerCode, $conn);
            echo "Referral count updated for referrer!<br>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>


