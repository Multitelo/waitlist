<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

include_once 'process/cone.php';

$username = 'Dear Qwestee';
$refid = '';
$message = '';
$reflink = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (empty($email)) {
        $message = "Please provide your email.";
        $redirect = true;
    } else {
        $stmt = $conn->prepare("SELECT username, refid FROM parti WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $username = $user['username'];
            $refid = $user['refid'];
            $reflink = "https://qwesty.site?r=" . urlencode($refid);

        } else {
            $stmt = $conn->prepare("SELECT * FROM resea WHERE bemail = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $message = "You joined as a Researcher.";
            } else {
                $message = "You are not part of the waitlist. Click <a href=\"https://qwesty.site\"> here</a> to join.";
                $redirect = true;
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Designed by Fusky and Coded by Multitelo -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Qwesty Waitlist</title>
    <link rel="stylesheet" type="text/css" href="css/ref.css" />
    <link rel="stylesheet" type="text/css" href="css/foot.css" />
  </head>
  <body>
    <div class="body">
      <nav>
        <a href="./index.php"><img src="images/questy png.png" alt="Qwesty Logo" srcset="" /></a>
      </nav>
      <main>
        <div>
          <form action="" method="post">
            <h1>Get your Referral link</h1>
            <label for="email"></label>
            <input
              type="email"
              name="email"
              id="email"
              placeholder="Enter Email"
              required
            /><br /><br />
            <input type="submit" value="Submit" />
          </form>
          <div class="efl">
            <p>
              <span style="color:#7F56D9; font-weight:600;"><?php echo htmlspecialchars($username); ?></span>, Refer a friend to Qwesty and go up the rank to
              earn free Qwess!. Your Referral Link is
            </p>
            <input
              type="url"
              name="refl"
              id="refoutput"
              value="<?php echo ($reflink); ?>
"
              readonly
            /><br /><br />
            <input type="button" value="Copy Link" class="copyb" onclick="copylinko()"/>
          </div>
          <p style="padding:10px; font-family: 'Space Grotesk', sans-serif; "><?php echo $message; ?></p>
        </div>
        <div class="qwessd">
          <h1>Qwess distribution method</h1>
          <p>The following rank system are being used to distribute qwess</p>
          <div class="container">
            <div class="box box1">45 qwess<br />1st to 3rd rank</div>
            <div class="box box2">40 qwess<br />4th to 10th rank</div>
            <div class="box box3">25 qwess<br />11th to 30th rank</div>
            <div class="box box4">15 qwess<br />31st to 70th rank</div>
            <div class="box box5">10 qwess<br />All other rank</div>
          </div>
        </div>
      </main>
      <footer>
        <div class="footimg">
          <img src="images/questy png.png" alt="" srcset="" />
        </div>
        <p>
          Qwesty gets useful data in order to make the market desire their
          product while rewarding it's users.
        </p>
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
    <script src="./script/waiting.js"></script>
  </body>
</html>
