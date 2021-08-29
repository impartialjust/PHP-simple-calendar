
<?PHP
require_once 'setting.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_COOKIE['userverify'])) {
    header('Location: login.php');
}
$hash = $_COOKIE['userverify'];
$sql = "SELECT account, expired, expiret FROM ecache WHERE cookies='$hash';";
$accountt = $conn->query($sql);
if ($accountt->num_rows > 0) {
    while ($row = $accountt->fetch_assoc()) {
        $account = $row["account"];
        $edate = $row["expired"];
        $etime = $row["expiret"];
        $date = date("Y-m-d");
        if ($date = $edate) {
            $nowhour = intval(date("H")) * 3600;
            $nowmins = intval(date("i")) * 60;
            $nowsecs = intval(date("s"));
            $nowtime = $nowhour + $nowmins + $nowsecs;
            $cookieshour = intval(substr($etime, 0, 2)) * 3600;
            $cookiesmins = intval(substr($etime, 3, 2)) * 60;
            $cookiessecs = intval(substr($etime, 5, 2));
            $cookiestime = $cookiessecs + $cookiesmins + $cookieshour;
            if ($nowtime < $cookiestime) {
                setcookie('userverify', "", time() - 3600, "/",);
            } else {
                echo "<div  style=\"width: 420px;padding: 10px;border: 5px solid red;margin: 0;\">Session Expired, <a href=\"login.php\">Please log in again</a></div>";
                unset($_COOKIE['userverfiy']);
                $conn->close();
            }
        } else {
            echo "<div  style=\"width: 420px;padding: 10px;border: 5px solid red;margin: 0;\">Session Expired, <a href=\"login.php\">Please log in again</a></div>";
            unset($_COOKIE['userverfiy']);
            $conn->close();
        }
    }
} else {
    echo "<div  style=\"width: 420px;padding: 10px;border: 5px solid red;margin: 0;\">Session Expired, <a href=\"login.php\">Please log in again</a></div>"; //no account
    $conn->close();
}
