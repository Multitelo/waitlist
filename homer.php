<?php
     include 'process/cone.php';

     session_start();
   
   // Check if the user is logged in
     if (!isset($_SESSION['bemail'])) {
         // Redirect to the login page if not logged in
         header("Location: index.php");
         exit();
     }
   
     // Access the session variable
     $email = $_SESSION['bemail'];

     // Fetch additional data for the user with the specified email
  $sqlUserData = "SELECT * FROM resea WHERE bemail = '$email'";
  $resultUserData = mysqli_query($conn, $sqlUserData);

  if (!$resultUserData) {
      echo "Error fetching user data: " . mysqli_error($conn);
      exit();
  }

  // Fetch the data
  $userData = mysqli_fetch_assoc($resultUserData);

  $id=$userData['id'];

  function posup($id) {
    if ($id == 1) {
        return 'st';
    } elseif ($id == 2) {
        return 'nd';
    } elseif ($id == 3) {
        return 'rd';
    } else {
        return 'th';
    }
  }

  // Output user-specific data
  // echo "Welcome, " . $userData['bizname'] . "! Thank You, for being part of the Waitlist.<br>";

    // Close the database connection
    mysqli_close($conn);


?>

<!DOCTYPE html>
<html lang="en">
    <head>
      <!-- Designed by Fusky and Coded by Multitelo -->
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Waitlist Page</title>
        <link rel="stylesheet" type="text/css" href="css/foot.css" />
        <link rel="stylesheet" type="text/css" href="css/homer.css" />
    </head>
    <body>
      <div class="body">
        <nav>
          <img src="images/questy png.png" alt="Qwesty Logo" srcset="" />
        </nav>
        <main>
            <div>
              <img src="images/pages.png" alt="" srcset="" />
            </div>
            <h1>congratulations <span><?php echo $userData['bizname'] ?></span> !, You're now part of Our Waitlist.</h1>
            <p><?php echo "Your Company Email is <b>$email!</b>"?> is<span> <?php echo $userData['id']?></span><sup><?php echo posup($id)?></sup> in Rank.</p>
        </main>
        <footer>
          <div class="footimg">
            <img src="images/questy png.png" alt="" srcset="">
          </div>
          <p>Qwesty gets useful data in order to make the market desire their product while rewarding it's users.</p>
          <div>
            <p>© 2023 Qwesty Team. All Rights Reserved.</p>
            <ul>
              <li><a href="#">Terms</a></li>
              <li><a href="#">Privacy</a></li>
              <li><a href="#">Cookies</a></li>
            </ul>
          </div>
        </footer>
      </div>
    </body>
</html>