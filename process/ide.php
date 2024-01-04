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


// Function to log in the user by setting a session (simplified example)
if (!function_exists('login')) {
    function login($email) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }
        $_SESSION['bemail'] = $email; // You may want to store more information in the session
        $_SESSION['last_activity'] = time(); // Update last activity timestamp
    }
}

// Start the session (if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get data from the form
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
        // User already part of the waitlist in another table
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
        login($email);
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
            login($email);
            // Display the results
            echo "
                <script>
                    alert('You have Successfully joined the Waitlist.');
                    window.location.href='../homer.php';
                </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}


// Close the database connection (only close it if it's still open)
if ($conn) {
    mysqli_close($conn);
}

?>