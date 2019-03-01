<html>
<head>
    <meta charset='UTF-8'>
    <title>Client Profile - Vulnerable Voting System</title>
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
                    Admin: TERRYLEUNG<br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#ECEDEF' align='CENTER'>
                <td width='25%'>Client Profile</td>
                <td width='25%'><a href='publish.php'>Vote Publish</a></td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Client Profile:</h3>
    <font color='#FF0000'>
        <span id='err_search'></span>
    </font>
    <form id='form_client_detail' name='form_client_detail' method='POST' action='client.php'>
        Email: <input type='TEXT' id='txt_email' name='txt_email' value='' />
        <input type='SUBMIT' id='submit_client_detail' name='submit_client_detail' value='Submit' />
    </form>

    <form id='form_account_status' name='form_account_status' method='GET' action='client.php'>
        Name: Chan Tai Man<br />
        HKID: A123456(7)<br />
        E-mail: chantaiman@gmail.com<input type='HIDDEN' id='hidden_email' name='hidden_email' value='chantaiman@gmail.com'><br />
        Phone: 98765432<br />
        Address: 8/F Po Kwong Building, 31-35 Shek Ku Lung Road, Mong Kok, Kowloon<br />
        Account Enable: <input type='CHECKBOX' id='chk_account_status' name='chk_account_status' />
    </form>

    <h3>Client Voting Record:</h3>
    <table border='0' cellpadding='5'>
        <tr bgcolor='#8AC007'>
            <th>Hot Topic</th>
            <th>My Choice</th>
        </tr>
        <tr bgcolor='#C1E0EB'>
            <td>Which course is your favourite one?</td>
            <td>COMP4632</td>
        </tr>
        <tr bgcolor='#C1E0EB'>
            <td>What overall rating would you give COMP4632?</td>
            <td>A+</td>
        </tr>
        <tr bgcolor='#C1E0EB'>
            <td>How easy is COMP4632 exam?</td>
            <td>Not my cup of tea</td>
        </tr>
    </table>
</body>
</html>