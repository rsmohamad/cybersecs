<?php
    session_start();
    isset($_SESSION["userId"]) or header("Location: login.php") and exit(0);
    include "../mysql.php";
    $conn = FALSE;
    $userId = $_SESSION["userId"];
    $userName = $_SESSION["userName"];
    $errmsg = "";

    $conn = dbOpen();
    if (!$conn) {
        $errmsg .= mysql_error()."<br />";
    }        
    else {
        $rows = retrieveAllUsers($conn);
    }
        
    dbClose($conn);
    $conn = FALSE;              

?>

<html>
<head>
    <meta charset='UTF-8'>
    <title>Admin Management - Vulnerable Voting System</title>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='2'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>Management Portal</h3>
                </td>
                <!-- Account Info -->
                <td align='RIGHT' valign='BOTTOM'>
                    Admin: <?=$userName;?><br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#ECEDEF' align='CENTER'>
                <td width='25%'><a href='client.php'>Client Profile</a></td>
                <td width='25%'><a href='publish.php'>Vote Publish</a></td>
                <td width='25%'>Manage</td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>All Users:</h3>
    <table border='0' cellpadding='5'>
        <tr bgcolor='#8AC007'>
            <th>Email</th>
            <th>Name</th>
            <th>HKID</th>
            <th>Phone</th>
            <th>Address</th>
        </tr>

        <?php
            for ($i=0; $i<count($rows); $i++) {
                $row = $rows[$i];
        ?>
            <tr bgcolor='#C1E0EB'>
                <td><?=$row["email"];?></td>
                <td><?=$row["name"];?></td>
                <td><?=$row["hkid"];?></td>
                <td><?=$row["phone"];?></td>
                <td><?=$row["address"];?></td>
            </tr>
        <?php
            }
        ?>
    </table>


</body>
</html>