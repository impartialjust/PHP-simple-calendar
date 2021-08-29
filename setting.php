<?PHP
$servername = "localhost"; //default as localhost
$username = "phpcal"; //MUST CHANGE
$password = "phpccc";//MUST CHANGE
$dbname = "phpcal"; //MUST CHANGE, The name of database
$location = "/"; //the root to this system, if the file is locate under a directory, it should be /thenameofdirectory/
/*
--------------------
Email server setting
--------------------
*/ 
$Mail["Host"]       = 'is.the.best';                          //Set the SMTP server to send through
$Mail["Auth"]       = true;                                   //Enable SMTP authentication
$Mail["account"]    = 'php@is.the.best';                      //SMTP username
$Mail["password"]   = 'mysecretpwd';                          //SMTP password
$Mail["SMTPSecure"] = 'ENCRYPTION_SMTPS';                     //Enable implicit TLS encryption or ENCRYPTION_STARTTLS
$Mail["Port"]       = 465;                                    //TCP port to connect to; use 587 if you have set `$Mail["SMTPSecure"] = ENCRYPTION_STARTTLS`
$Mail["Sender"]     = 'php@is.the.best';                      //Should be as same as the account options or any other email name you own
$Mail["Sendername"] = 'PHP simple calender auto mailer';      //Can be any name