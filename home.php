<?php
  include 'process/cone.php';

  session_start();

 // Check if the user is logged in
  if (!isset($_SESSION['email'])) {
      // Redirect to the login page if not logged in
      header("Location: index.php");
      exit();
  }

  // Access the session variable
  $email = $_SESSION['email'];

  // Retrieve positions based on the highest referral count
  $sqlRetrieve = "SELECT id, refcount, (SELECT COUNT(*) + 1 FROM parti AS p2 WHERE p2.refcount > p1.refcount OR (p2.refcount = p1.refcount AND p2.id < p1.id)) AS position FROM parti AS p1 ORDER BY refcount DESC, id ASC";
  $resultPositions = mysqli_query($conn, $sqlRetrieve);

  if (!$resultPositions) {
      echo "Error fetching positions: " . mysqli_error($conn);
      exit();
  }

  // Fetch additional data for the user with the specified email
  $sqlUserData = "SELECT * FROM parti WHERE email = '$email'";
  $resultUserData = mysqli_query($conn, $sqlUserData);

  if (!$resultUserData) {
      echo "Error fetching user data: " . mysqli_error($conn);
      exit();
  }

  // Fetch the data
  $userData = mysqli_fetch_assoc($resultUserData);

  // Output user-specific data
  // echo "Welcome, " . $userData['username'] . "! You are logged in.<br>";

  // Output referral count position for the associated email
  $position = null;
  while ($row = mysqli_fetch_assoc($resultPositions)) {
      if ($row['id'] == $userData['id']) {
          $position = $row['position'];
          break;
      }
  }

  function posup($position) {
    if ($position == 1) {
        return 'st';
    } elseif ($position == 2) {
        return 'nd';
    } elseif ($position == 3) {
        return 'rd';
    } else {
        return 'th';
    }
  }
  

  // if ($position !== null) {
  //     echo "Your Referral Count Position: " . $position . "<br>";
  // } else {
  //     echo "Position not found for the specified email.";
  // }

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
    <!-- <link rel="stylesheet" type="text/css" href="css/homer.css" /> -->
    <link rel="stylesheet" type="text/css" href="css/home.css" />
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
          <h1><span><?php echo $userData['username']?></span>!, You're #<?php echo $position?><sup><?php echo posup($position) ;?></sup> in Rank.</h1>
          <p><?php echo "Your Email address is <b>$email!</b>"?></p>
          <button type="button" onclick="referb()" id="refb"><a href="#brefer">Go Up The Waiting List</a></button>
      </main>
    <div id="brefer" style="display: none;">
      <div>
        <div>
          <img src="images/dancing.png" alt="" srcset="" />
        </div>
        <div>
          <h1>Invite your friends</h1>
          <p>You have Referred <?php echo $userData['refcount']?> individual.</p>
        </div>
      </div>
      <div>
        <input type="text" id="myinput" style="display: none;" value="<?php echo "index.php?r=" . $userData['refid'];?>" readonly>
        <input type="button" value="Copy Link" onclick="copylink()">
        <input type="button" value="Share Link" onclick="sharelink()">
      </div>
    </div>
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
    <script src="script/waiting.js"></script>
  </body>
</html>
