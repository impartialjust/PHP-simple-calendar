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
                echo "log in as" . " " . $account . "<br>";
                $date = date("Y-m-d");
                $sql = "SELECT etime, edate, things, elength FROM edata WHERE account='$account'AND edate='$date'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        $timeneed = $row["elength"];
                        $ptime = $timeneed + intval(substr($row["etime"], 3, 2));
                        $gsecs = intval(substr($row["etime"], 5, 2));
                        if ($ptime >= 60) {
                            $gtime = $ptime - 60;
                            $ghours =  intval(substr($row["etime"], 0, 2)) + 1;
                            $gsecs = intval(substr($row["etime"], 5, 2));
                        } else {
                            $gtime = $ptime;
                            $ghours = intval(substr($row["etime"], 0, 2));
                            $gsecs = intval(substr($row["etime"], 5, 2));
                        }
                        $thetime = $ghours . ":" . $gtime . ":" . $gsecs;
                        echo "things: " . $row["things"] . " - Time: " . $row["etime"] . " " . $row["edate"] . "-Time should finish:" . $thetime . "<br>";
                        $time = $row["etime"];
                    }
                    $conn->close();
                } else {
                    echo "No result";
                    $conn->close();
                }
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

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<br>
<h3>Count down</h3>
<div id="countdown"></div>
<button onclick="renew();">Refresh</button>
<button onclick="keep();">Keep</button>
<hr>
<form>
    Time: <input class="pendingtime" id="pending" name="time" type="text" value="5">
    Submit: <input class="submitbutton" id="go" name="start" type="button" value="GO"></form>
<script>
    var inputText = document.getElementById("pending");
    inputText.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("go").click();
        }
    });
    $('.submitbutton').on('click', function(e) {
        // Stop the browser from doing anything else
        e.preventDefault();
        // Do an AJAX post
        $.ajax({
            type: "POST",
            url: "panding.php",
            data: {
                time: parseInt(document.getElementById("pending").value) + parseInt(<?PHP require_once 'length.php' ?>),
                etime: '<?PHP require_once 'gettime.php' ?>', // various ways to store the ID, you can choose
            },
            success: function(data) {
                // POST was successful - do something with the response
                alert("Pending for " + data);
            },
            error: function(data) {
                // return http status code
                alert(data.responseText);
            }
        });
    });
</script>
<script>
    function renew() {
        let currentDate = new Date();
        let nexttime = '<?PHP require_once 'gettime.php' ?>';
        let hournext = parseInt(nexttime.substring(0, 2)) * 3600;
        let hournow = currentDate.getHours() * 3600;
        let minsnext = parseInt(nexttime.substring(3, 5)) * 60;
        let minsnow = currentDate.getMinutes() * 60;
        let secsnext = parseInt(nexttime.substring(6, 8));
        let secsnow = currentDate.getSeconds();
        let timenext = hournext + minsnext + secsnext;
        let timenow = hournow + minsnow + secsnow;
        let countdown = timenext - timenow;
        window.rsss = secondsToHms(countdown, timenext, timenow);
        document.getElementById("countdown").innerHTML = window.rsss;
    }

    function secondsToHms(d, timenext, timenow) {
        d = Number(d);
        var h = Math.floor(d / 3600);
        var m = Math.floor(d % 3600 / 60);
        var s = Math.floor(d % 3600 % 60);
        if (timenext > timenow) {
            return h + ' hours ' + m + ' mins ' + s + ' secs ';
        } else {
            return 'passed';
        }
    }

    function keep() {
        setInterval(function() {
            renew();
        }, 1000);
    }
</script>