<?php
session_start();
include "mysql.php";
$conn = FALSE;
$loginId = trim($_POST["txt_login"]);
$password = $_POST["pwd_login"];
$errmsg = "";
if (isset($_POST["submit_login"])) {
    $conn = dbOpen();
    if (!$conn) {
        $errmsg .= mysql_error()."<br />";
    }
    else {
        $rows = retrieveUserByEmailPassword($conn, $loginId, $password);
        if (is_null($rows)) {
            $errmsg .= mysql_error()."<br />";
        }
        else if (count($rows)!=1) {
            $errmsg .= "Login failed!<br />";
        }
        else if ($rows[0]["status"]!='E') {
            $errmsg .= "Account disabled!<br />";
        }

        else {
            $row = $rows[0];
            $_SESSION["userId"] = $row["_id"];
            $_SESSION["userName"] = $row["name"];
            header("Location: user/profile.php?login=".$loginId);
        }
    }
    dbClose($conn);
    $conn = FALSE;
}
?>

<html>
<head>
    <meta charset='UTF-8'>
    <title>Vulnerable Voting System</title>

    <style>
        body {
            background-image: url('images/bg.jpg');
            background-repeat: no-repeat;
            width: 100%;
        }
        
        div {
            position: absolute;
            top: 320px;
            left: 640px;
            width: 230px;
            height: 120px;
            border: 5px solid #8AC007;
            padding: 10px 10px 10px 10px;
            line-height: 200%;
        }
    </style>

    <script type='text/javascript'>
        function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
        
        function validateLogin() {
            var txtLogin = document.getElementById("txt_login").value;
            var pwdLogin = document.getElementById("pwd_login").value;
            var errmsg = "";
        if (txtLogin=="") {
            errmsg += "Login email is missing!<br />";
        }
        else if (!validateEmail(txtLogin)) {
            errmsg += "Login incorrect!<br />";
        }
        if (pwdLogin=="") {
            errmsg += "Password is missing!<br />";
        }
            document.getElementById("err_login").innerHTML = errmsg;
            return(errmsg=="");
        }
    </script>

</head>

<body>
    <div>
        <form id='form_login' name='form_login' method='POST' action='index.php'>
            EMAIL: <input type='TEXT' id='txt_login' name='txt_login' value='' size='16' /><br />
            PASSWORD: <input type='PASSWORD' id='pwd_login' name='pwd_login' value='' size='16' /><br />
            <input type='SUBMIT' id='submit_login' name='submit_login' value='Login' onclick='javascript: return validateLogin();' />
            <a href='registration.php?url=index.php'>Registration</a>
        </form>
        <font color='#FF0000'>
            <span id='err_login'></span>
        </font>
    </div>
</body>
</html>