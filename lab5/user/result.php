<?php
    session_start();
    isset($_SESSION["userId"]) or header("Location: login.php") and exit(0);

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
        $rows = retrieveUserVoteByEmail($conn, $email);
    }
?>

<html>
<head>
    <meta charset='UTF-8'>
    <title>User Result - Vulnerable Voting System</title>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='3'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>User Result</h3>
                </td>
                <!-- userinfo -->
                <td align='RIGHT' valign='BOTTOM'>
                <?=$userName;?><br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#8AC007' align='CENTER'>
                <td width='25%'><a href='profile.php?login=<?=$email;?>'>Profile</a></td>
                <td width='25%'><a href='voting.php?login=<?=$email;?>'>Voting</a></td>
                <td width='25%'>Result</td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Voting Record:</h3>
    <table border='0' cellpadding='5'>
        <tr bgcolor='#8AC007'>
            <th>Hot Topic</th>
            <th>My Choice</th>
        </tr>

        <?php
            for ($i=0; $i<count($rows); $i++) {
                $row = $rows[$i];
                $opt = "option_a";
                switch ($row["choice"]) {
                    case '2': $opt = "option_b"; break;
                    case '3': $opt = "option_c"; break;
                    case '4': $opt = "option_d"; break;
                }
        ?>
            <tr bgcolor='#C1E0EB'>
                <td><?=$row["topic"];?></td>
                <td><?=$row[$opt];?></td>
            </tr>
        <?php
            }
        ?>
    </table>
</body>
</html>