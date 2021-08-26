<?PHP

require_once 'demoset.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (strpos($conn->server_info, "MariaDB") == TRUE or strpos($conn->server_info, "MySQL") == TRUE) {
    echo "Enviroment check... Success\n";
} else {
    exit("Must use MariaDB or MySQL, do not support Microsoft server. If you are using PostgreSQL or SQLite, you can try again as y.");
}


$sql = "CREATE TABLE edata (
    etime time,
    edate date,
    things text,
    account tinytext,
    elength int(11)
);";
$createedata = $conn->query($sql);
if ($createedata == 1) {
    echo "Creating edata table... Success\n";
} else {
    echo "Creating edata table... Fail\n";
}
$sql = "CREATE TABLE ecache (
    cookies tinytext,
    account tinytext,
    expired date,
    expiret time
);";
$createecache = $conn->query($sql);
if ($createecache == 1) {
    echo "Creating ecache table... Success\n";
} else {
    echo "Creating ecache table... Fail\n";
}
$sql = "CREATE TABLE accountinfo (
    account tinytext,
    pwd text
);";
$createaccountinfo = $conn->query($sql);
if ($createaccountinfo == 1) {
    echo "Creating accouninfo table... Success\n";
} else {
    echo "Creating accountinfo table... Fail\n";
}
echo "Your account :";
$accountname = trim(fgets(STDIN));
echo "Your password :";
$accountpwd1 = trim(fgets(STDIN));
echo "Confirm password :";
$accountpwd2 = trim(fgets(STDIN));
echo $accountpwd1 . "\n";
echo $accountpwd2 . "\n";
//password confirm error always see the same password not debugged yet

while (true) {
    if ($accountpwd1 == $accountpwd2) {
        break;
    }
    echo "Password does not matched, please try again.\n";
    echo "Your password :";
    $accountpwd1 = trim(fgets(STDIN));
    echo "Confirm password :";
    $accountpwd2 = trim(fgets(STDIN));
}

echo "Record writing... ";
$password = hash('sha3-512', $accountpwd1);
$sql = "INSERT INTO accountinfo (account, pwd)
VALUES ('$accountname', '$password')";
$createaccountinforecord = $conn->query($sql);
if ($createaccountinforecord == 1) {
    echo "Success\n";
} else {
    echo "Fail\n";
}

echo "Installion complete...";
