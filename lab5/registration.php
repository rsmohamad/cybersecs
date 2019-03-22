<?php
    include "mysql.php";
    include "auth.php";

    $conn = FALSE;
    $url = trim($_GET["url"]);
    $email = trim($_POST["txt_email"]);
    $password = trim($_POST["pwd_create"]);
    $name = trim($_POST["txt_name"]);
    $hkid = trim($_POST["txt_hkid"]).trim($_POST["txt_hkid_checkdigit"]);
    $phone = trim($_POST["txt_phone"]);
    $address = trim($_POST["txt_address"]);
    $secret = trim($_POST["txt_secret"]);
    $token = trim($_POST["txt_token"]);

    $conn = dbOpen();
    if (!$conn) {
        $errmsg .= mysql_error()."<br />";
    }
    
    $rows = retrieveUserByMaxId($conn);
    if (is_null($rows)) {
        $errmsg .= mysql_error()."<br />";
    }
    else {
        $secret = $rows[0]["max_id"];
        for ($i=0; $i<=9; $i++) {
            $secret = str_replace(chr($i+48), chr($i+65), $secret);
        }
    }
    dbClose($conn);
    $conn = FALSE;

    $errmsg = "";
    if (isset($_POST["submit_register"])) {
        $conn = dbOpen();
        if (!$conn) {
            $errmsg .= mysql_error()."<br />";
        }
        $rows = retrieveUserByEmailHkid($conn, $email, $hkid);
        if (is_null($rows)) {
            $errmsg .= mysql_error()."<br />";
        }
        else if (count($rows)!=0) {
            $errmsg .= "User already registered!<br />";
        }
        else if (!verify($secret, $token)) {
            $errmsg .= "Token invalid!<br />";
        }
        else {
            $res = createUser($conn, $email, $password, $name, $hkid, $phone, $address, "E");
            if (!$res) {
                $errmsg .= mysql_error()."<br />";
            } else {
                header("Location: ".$url);
            }
        }
        dbClose($conn);
        $conn = FALSE;
    }

?>

<html>
<head>
    <meta charset='UTF-8'>
    <title>User Registration - Vulnerable Voting System</title>

    <script type='text/javascript'>

        function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }

        function validateHkid(hkid) {
            var re = /[a-zA-Z]{1,2}(\d{6})/
            return re.test(String(email));
        }
        
        function validateRegistration() {
        var txtEmail = document.getElementById("txt_email").value;
        var pwdNew = document.getElementById("pwd_create").value;
        var pwdConfirm = document.getElementById("pwd_confirm").value;
        var txtName = document.getElementById("txt_name").value;
        var txtHkid = document.getElementById("txt_hkid").value;
        var txtHkidChk = document.getElementById("txt_hkid_checkdigit").value;
        var txtPhone = document.getElementById("txt_phone").value;
        var txtAddress = document.getElementById("txt_address").value;
        var chkDeclare = document.getElementById("chk_declare").checked;
        var errmsg = "";

        var txtToken = document.getElementById("txt_token").value;
        if (txtToken=="") {
        errmsg += "Token is missing!<br />";
        }
        else if (txtToken.length!=6) {
        errmsg += "Token invalid!<br />";
        }
        else if (txtToken!=(""+parseInt(txtToken, 10))) {
        errmsg += "Token invalid!<br />";
        }

        if (txtEmail=="") {
        errmsg += "Email is missing!<br />";
        }
        else if (txtEmail.length>100) {
        errmsg += "Email too long!<br />";
        }
        else if (!validateEmail(txtEmail)) {
        errmsg += "Email invalid!<br />";
        }
        if (pwdNew=="" || pwdConfirm=="") {
        errmsg += "Password is missing!<br />";
        }
        else if (pwdNew.length<6) {
        errmsg += "Password too short!<br />";
        }
        else if (pwdNew.length>30) {
        errmsg += "Password too long!<br />";
        }
        else if (pwdNew!=pwdConfirm) {
        errmsg += "Password not match!<br />";
        }
        if (txtName=="") {
        errmsg += "Name is missing!<br />";
        }
        else if (txtName.length>50) {
        errmsg += "Name too long!<br />";
        }
        else if (txtName.match(/[^A-Za-z ]/)) {
        errmsg += "Name invalid!<br />";
        }
        if (txtHkid=="") {
        errmsg += "HKID is missing!<br />";
        }
        else if (txtHkid.length!=7 && txtHkid.length!=8) {
        errmsg += "HKID format invalid!<br />";
        }
        else if (!validateHkid(txtHkid)) {
        errmsg += "HKID format invalid!<br />";
        }
        if (txtHkidChk=="") {
        errmsg += "HKID incomplete!<br />";
        }
        else if (txtHkidChk.length!=1) {
        errmsg += "HKID format invalid!<br />";
        }
        
        if (txtPhone=="") {
        errmsg += "Phone is missing!<br />";
        }
        else if (txtPhone.length!=8) {
        errmsg += "Phone number invalid!<br />";
        }
        else if (txtPhone!=(""+parseInt(txtPhone, 10))) {
        errmsg += "Phone number invalid!<br />";
        }
        if (txtAddress=="") {
        errmsg += "Address is missing!<br />";
        }
        else if (txtAddress.length>1000) {
        errmsg += "Address too long!<br />";
        }
        if (chkDeclare==false) {
        errmsg += "Declaration not yet accepted!<br />";
        }
        document.getElementById("err_registration").innerHTML = errmsg;
        return(errmsg=="");
        }
    </script>
</head>

<body>
    <h2>Vulnerable Voting System</h2>
    <h3>User Registration</h3>
    <font color='#FF0000'>
        <span id='err_registration'><?=$errmsg;?></span>
    </font>
    <form id='form_registration' name='form_registration' method='POST' action='registration.php?url=index.php'>
        Email: <input type='TEXT' id='txt_email' name='txt_email' value='' size='40' /><br />
        Password: <input type='PASSWORD' id='pwd_create' name='pwd_create' value='' size='16' /><br />
        Retype Password: <input type='PASSWORD' id='pwd_confirm' name='pwd_confirm' value='' size='16' /><br />
        Full Name: <input type='TEXT' id='txt_name' name='txt_name' value='' size='30' /><br />
        HKID: <input type='TEXT' id='txt_hkid' name='txt_hkid' value='' size='8' /> (<input type='TEXT' id='txt_hkid_checkdigit' name='txt_hkid_checkdigit' value='' size='1' />)<br />
        Phone: <input type='TEXT' id='txt_phone' name='txt_phone' value='' size='11' /><br />
        Address: <input type='TEXT' id='txt_address' name='txt_address' value='' size='80' /><br />
        Secret Key: <input type='HIDDEN' id='txt_secret' name='txt_secret' value='<?=$secret;?>' /><?=$secret;?><br />
        Token: <input type='TEXT' id='txt_token' name='txt_token' value='' size='6' /><br/>
        <input type='CHECKBOX' id='chk_declare' name='chk_declare' /> I accept the terms and conditions of this system.<br />
        <input type='SUBMIT' id='submit_register' name='submit_register' value='Register' onclick='javascript: return validateRegistration();'/><br />
    </form>

    QR Code: <img src='https://chart.googleapis.com/chart?chs=100x100&chld=M|0&cht=qr&chl=otpauth://totp/comp4632?secret=<?=$secret;?>'><br />
</body>
</html>