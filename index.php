<!-- <?php 
  header("Cache-Control: no-cache, no-store, must-revalidate");
  header("Pragma: no-cache");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");


  include_once 'process/cone.php';
  // include_once 'process/inde.php';
  // include_once 'process/ide.php';

    
   // $referer = $_GET['r'];
    // Initialize the variable to avoid "undefined variable" error
  $referer = "";

  // Check if the referral code parameter exists in the URL
  if (isset($_GET['r'])) {
      // Retrieve the referral code from the URL
      $referer = $_GET['r'];
    // Now you can use $referer as needed in your page
    // echo "Referral Code: " . htmlspecialchars($referer);
  }

  
  // Perform the query to get the count of researchers
  $queryResearcher = "SELECT COUNT(*) AS researcher_count FROM resea";
  $resultResearcher = mysqli_query($conn, $queryResearcher);

  // Perform the query to get the count of participants
  $queryParticipant = "SELECT COUNT(*) AS participant_count FROM parti";
  $resultParticipant = mysqli_query($conn, $queryParticipant);

  // Initialize counts
  $researcherCount = 0;
  $participantCount = 0;

  // Fetch count for researchers
  if ($resultResearcher) {
      $rowResearcher = mysqli_fetch_assoc($resultResearcher);
      $researcherCount = $rowResearcher['researcher_count'];
      echo "Researcher count: " . $researcherCount . " users<br>";

      // Free the result set
      mysqli_free_result($resultResearcher);
  } else {
      // Handle query error for researchers
      echo "Error fetching Researcher count: " . mysqli_error($conn);
  }

  // Fetch count for participants
  if ($resultParticipant) {
      $rowParticipant = mysqli_fetch_assoc($resultParticipant);
      $participantCount = $rowParticipant['participant_count'];
      echo "Participant count: " . $participantCount . " users<br>";

      // Free the result set
      mysqli_free_result($resultParticipant);
  } else {
      // Handle query error for participants
      echo "Error fetching Participant count: " . mysqli_error($conn);
  }

  // Calculate and display the total count
  $totalCount = $researcherCount + $participantCount;
  echo "Total users: " . $totalCount . " users<br>";

  // Close the database connection
  mysqli_close($conn);
?> -->


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Designed by Fusky and Coded by Multitelo -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Qwesty Waitlist</title>
    <link rel="stylesheet" type="text/css" href="css/inde.css" />
    <link rel="stylesheet" type="text/css" href="css/design.css" />
    <link rel="stylesheet" type="text/css" href="css/foot.css" />
  </head>
  <body>
    <div class="body">
      <nav>
        <img src="images/questy png.png" alt="Qwesty Logo" srcset="" />
      </nav>
      <main>
        <div>
          <h1><span>Qwesty</span> is Coming</h1>
          <p>until then</p>
          <button type="button" onclick="joinWaitlist" id="joinw">
            <a href="#select"><span></span><span></span><span></span><span></span> Join Waitlist</a>
          </button>
          
          <form
            action="process/test.php"
            method="post"
            id="joinap"
            style="display: none;"
            class="jwf"
          >
            <h1>Fill Up to Join</h1>
            <input
              type="email"
              name="email"
              id="email"
              placeholder="Enter your Email"
            />
            <input
              type="text"
              name="username"
              id="usena"
              placeholder="Username"
            />
            <input
              type="hidden"
              name="referrer"
              value="<?php
            echo  htmlspecialchars($referer);?>"
            />

            <input type="submit" value="Submit" />
          </form>
          <form
            action="process/test.php"
            method="post"
            id="joinar"
            style="display: none"
            class="jwf"
          >
            <h1>Fill Up to Join</h1>
            <input
              type="email"
              name="bemail"
              id="email"
              placeholder="Enter Company Email"
            />
            <input
              type="text"
              name="bizname"
              id="usena"
              placeholder="Business Name"
            />
            <!-- <input type="hidden" name="referer" value="'.$referer.';"> -->
            <input type="submit" value="Submit" />
          </form>
          <p class="trc" title="<?php echo "$researcherCount Researchers and $participantCount Participants"?>"><?php echo $totalCount ?> persons has joined the waitlist</p>
          <p>
            Qwesty help UX <span>Researchers</span>  get useful data in order to make the
            market desire their product while helping the <span>participants</span> enjoy and
            get rewards for the data they share.
          </p>
          
        </div>
        <div id="select">
          <div class="joinr">
            <div id="resea">
              <figure class="myslides fade">
                <img src="images/youngw.png" alt="" srcset="" />
                <figcaption>UX researcher</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/1management.png" alt="" srcset="" />
                <figcaption>Product manager</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/Project.png" alt="" srcset="" />
                <figcaption>UX designer</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/youngw.png" alt="" srcset="" />
                <figcaption>UX writer</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/Consulting.png" alt="" srcset="" />
                <figcaption>Data Analyst</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/other.png" alt="" srcset="" />
                <figcaption>Solopreneur</figcaption>
              </figure>
            </div>
            <div class="butcon"><button onclick="joinWaitlistar()">
              <a href="#joinar">Join as Researcher</a>
            </button></div>
            
          </div>
          <div class="joinp">
            <div id="parti">
              <figure class="myslides fade">
                <img src="images/student.png" alt="" srcset="" />
                <figcaption>Students</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/woke.png" alt="" srcset="" />
                <figcaption>Workers</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/standing.png" alt="" srcset="" />
                <figcaption>Entrepreneurs</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/desk.png" alt="" srcset="" />
                <figcaption>Stay-at-Home parent</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/Project.png" alt="" srcset="" />
                <figcaption>Celebrities</figcaption>
              </figure>
              <figure class="myslides fade">
                <img src="images/chart.png" alt="" srcset="" />
                <figcaption>Investors/Traders</figcaption>
              </figure>
            </div>
            <div><button onclick="joinWaitlistap()">
              <a href="#joinap">Join as Participant</a>
            </button></div>
            
          </div>
        </div>
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
  <script src="script/waiting.js"></script>
</html>
