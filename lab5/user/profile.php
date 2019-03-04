<?php
    session_start();
    isset($_SESSION["userId"]) or header("Location: login.php") and exit(0);

    require_once("../phpThumb_1.7.9/phpThumb.config.php");
    include "../mysql.php";
    $conn = FALSE;
    $userId = $_SESSION["userId"];
    $userName = $_SESSION["userName"];
    $email = trim($_GET["login"]);
    $phone = trim($_POST["txt_phone"]);
    $address = trim($_POST["txt_address"]);
    $password = $_POST["pwd_new"];
    $errmsgUpload = "";
    $errmsgUpdate = "";
    $errmsgChange = "";
    $conn = dbOpen();

    if (!$conn) {
        $errmsgUpload .= mysql_error()."<br />";
    }
    else {
        $rows = retrieveUserByEmail($conn, $email);
        if (is_null($rows)) {
            $errmsgUpload .= mysql_error()."<br />";
        }
        else if (count($rows)!=1) {
            $errmsgUpload .= "Login is invalid!<br />";
        }
        else {
            $row = $rows[0];
            $uploadPath = '../images/';
            $uploadFile = $uploadPath.$row["_id"];
            if (isset($_POST["submit_upload"])) {
                $isUploaded = move_uploaded_file($_FILES["file_upload"]["tmp_name"], $uploadFile);
                 if (!$isUploaded) {
                    switch ($_FILES["file_upload"]["error"]) {
                        case UPLOAD_ERR_INI_SIZE:
                            $errmsgUpload .= "The uploaded file exceeds the maximum file size limit!<br />";
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            $errmsgUpload .= "The uploaded file exceeds the maximum file size limit!<br />";
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $errmsgUpload .= "The uploaded file was only partially uploaded!<br />";
                            break;
                        default:
                            $errmsgUpload .= "Unknown error!<br />";
                    }
                 }
                 else if (strcmp(mime_content_type($uploadFile), "image/png")!=0 &&
                            strcmp(mime_content_type($uploadFile), "image/jpeg")!=0 &&
                            strcmp(mime_content_type($uploadFile), "image/gif")!=0) {
                        $errmsgUpload .= "Image type invalid!<br />";
                        copy($uploadPath."profile.png", $uploadFile);
                 }
            }
            else if (isset($_POST["submit_update"])) {
                $res = updateUserPhoneAndAddress($conn, $email, $phone, $address);
                if (!$res) {
                    $errmsgUpdate .= mysql_error()."<br />";
                }
                $rows = retrieveUserByEmail($conn, $email);
                $row = $rows[0];
            }
            else if (isset($_POST["submit_change"])) {
                $res = updateUserPassword($conn, $email, $password);
                if (!$res) {
                    $errmsgChange .= mysql_error()."<br />";
                }
                $rows = retrieveUserByEmail($conn, $email);
                $row = $rows[0];
            }
        }
    }
 dbClose($conn);
 $conn = FALSE;
?>

<html>
<head>
    <meta charset='UTF-8'>
    <title>User profile - Vulnerable Voting System</title>


    <script type='text/javascript'>
        function toggleUpload(isDisplay) {
            var btnUpload = document.getElementById("btn_upload");
            var fileUpload = document.getElementById("file_upload");
            var submitUpload = document.getElementById("submit_upload");
        
            if (isDisplay) {
                btnUpload.style.display="none";
                fileUpload.style.display="block";
                submitUpload.style.display="block";
            }
            else {
                btnUpload.style.display="block";
                fileUpload.style.display="none";
                submitUpload.style.display="none";
            }
        }

        function validateInfo() {
            var phone = document.getElementById("txt_phone").value;
            var addr = document.getElementById("txt_address").value;
            var errmsg = "";

            if (phone.length == 0 || !/[0-9]{8}/.test(phone)) {
                errmsg += "Invalid phone number";
            }

            if (phone.length == 0) {
                errmsg += "Address must be filled";
            }

            return errmsg;
        }

        function validatePassword() {
            var passwd = document.getElementById("pwd_new").value;
            var confirm = document.getElementById("pwd_confirm").value;
            return passwd === confirm;
        }
    </script>

</head>

<body  onload='javascript: toggleUpload(false);'>
    <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='3'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>User profile</h3>
                </td>
                <!-- userinfo -->
                <td align='LEFT' valign='BOTTOM'>
                <?=$row["name"];?><br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#8AC007' align='CENTER'>
                <td width='25%'>Profile</td>
                <td width='25%'><a href='voting.php?login=<?=$email;?>'>Voting</a></td>
                <td width='25%'><a href='result.php?login=<?=$email;?>'>Result</a></td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <div class='float' style="width: 33%; float: right;">

        <?php if (file_exists($uploadFile)) { ?>
            <img src='<?=htmlspecialchars(phpThumbURL("src=".$uploadFile));?>' alt='profilepic' height='150' /><br />
        <?php } else { ?>
            <img src='<?=htmlspecialchars(phpThumbURL("src=../images/profile.png"));?>' alt='profile pic' height='150' /><br />
        <?php } ?>

        <form id='form_upload' name='form_upload' enctype='multipart/form-data' method='POST' action='profile.php?login=<?=$email;?>'>

            <input type='BUTTON' id='btn_upload' name='btn_upload' value='Upload profile pic'
            onclick='javascript: toggleUpload(true);'>

            <input type='HIDDEN' id='MAX_FILE_SIZE' name='MAX_FILE_SIZE' value='1000000' />
            <input type='FILE' id='file_upload' name='file_upload'>

            <input type='SUBMIT' id='submit_upload' name='submit_upload' value='Upload'
            onclick='javascript: toggleUpload(false);'>

        </form>

    </div>



    <h3>Account Information:</h3>

    <font color='#FF0000'>
        <span id='err_upload'></span>
    </font>
    Name: <?=$row["name"];?><br />

    Email: <?=$row["email"];?><br />

    Phone: <?=$row["phone"];?><br />

    Address: <?=$row["address"];?><br />



    <h3>Update Information:</h3>

    <font color='#FF0000'>

        <span id='err_update'><?=$errmsgUpdate;?></span>

    </font>

    <form id='form_update' name='form_update' enctype='multipart/form-data' method='POST' action='profile.php?login=<?=$email;?>'>

        Phone: <input type='TEXT' id='txt_phone' name='txt_phone' value='' size='11' /><br />

        Address: <input type='TEXT' id='txt_address' name='txt_address' value='' size='80' /><br />

        <input type='SUBMIT' id='submit_update' name='submit_update' value='Update' onclick='javascript: return validateInfo();' /><br />

    </form>



    <h3>Change Password:</h3>

    <font color='#FF0000'>

        <span id='err_change'><?=$errmsgChange;?></span>

    </font>

    <form id='form_change' name='form_change' method='POST' action='profile.php?login=<?=$email;?>'>

        New Password: <input type='PASSWORD' id='pwd_new' name='pwd_new' value='' size='16' /><br />

        Confirm Passowrd: <input type='PASSWORD' id='pwd_confirm' name='pwd_confirm' value='' size='16' /><br />

        <input type='SUBMIT' id='submit_change' name='submit_change' value='Change' onclick='javascript: return validatePassword();' /><br />

    </form>

</body>
</html>