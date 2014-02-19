
<?php

$dir = '/temp/aaa';

// set up basic connection
$conn_id = ftp_connect("localhost");

// login with username and password
$login_result = ftp_login($conn_id, "webuser", "zaqwsx");

// try to create the directory $dir
if (ftp_mkdir($conn_id, $dir)) {
 echo "successfully created $dir\n";
} else {
 echo "There was a problem while creating $dir\n";
}

// close the connection
ftp_close($conn_id);
?>

