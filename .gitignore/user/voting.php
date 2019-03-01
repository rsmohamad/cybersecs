<?php
     session_start();
     isset($_SESSION["userId"]) or header("Location: login.php") and exit(0);
?>

<html>
<head>
    <meta charset='UTF-8'>
    <title>User Voting - Vulnerable Voting System</title>

    <script>
        function validateVote() {
            return "haha";
        }
    </script>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='3'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>User Voting</h3>
                </td>
                <!-- userinfo -->
                <td align='RIGHT' valign='BOTTOM'>
                    Chan Tai Man<br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#8AC007' align='CENTER'>
                <td width='25%'><a href='profile.php?login=chantaiman@hotmail.com'>Profile</a></td>
                <td width='25%'>Voting</td>
                <td width='25%'><a href='result.php?login=chantaiman@hotmail.com'>Result</a></td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Vote Now:</h3>
    <font color='#FF0000'>
        <span id='err_vote'></span>
    </font>
    <form id='form_topic_search' name='form_topic_search' method='POST' action='voting.php?login=chantaiman@hotmail.com'>
        Topic: <input type='TEXT' id='txt_search' name='txt_search' value='' />
        <input type='SUBMIT' id='submit_search' name='submit_search' value='Search' />
    </form>

    <form id='form_vote' name='form_vote' method='POST' action='voting.php?login=chantaiman@hotmail.com'>
        <table border='0'>
            <tr bgcolor='#8AC007'>
                <th></th>
                <th>Hot Topic</th>
                <th>Option A</th>
                <th>Option B</th>
                <th>Option C</th>
                <th>Option D</th>
            </tr>
            <tr bgcolor='#C1E0EB'>
                <td><input type='RADIO' id='radio_topic' name='radio_topic' value='11'></td>
                <td>Which course is your favourite one?</td>
                <td>COMP4631</td>
                <td>COMP4632</td>
                <td>COMP4633</td>
                <td>COMP4634</td>
            </tr>
            <tr bgcolor='#C1E0EB'>
                <td><input type='RADIO' id='radio_topic' name='radio_topic' value='20'></td>
                <td>What overall rating would you give COMP4632?</td>
                <td>A</td>
                <td>A+</td>
                <td>A++</td>
                <td>A+++</td>
            </tr>
            <tr bgcolor='#C1E0EB'>
                <td><input type='RADIO' id='radio_topic' name='radio_topic' value='39'></td>
                <td>How easy is COMP4632 assignment?</td>
                <td>Just a piece of cake</td>
                <td>Not my cup of tea</td>
                <td>A hard nut to crack</td>
                <td>To go bananas with it</td>
            </tr>
            <tr bgcolor='#C1E0EB'>
                <td><input type='RADIO' id='radio_topic' name='radio_topic' value='45'></td>
                <td>When is the best date for COMP4632 mid-term exam?</td>
                <td>next hour</td>
                <td>next week</td>
                <td>next month</td>
                <td>next semester</td>
            </tr>
        </table>
        <br />
        My Option:
        <input type='RADIO' id='radio_option' name='radio_option' value='1'>A / 
        <input type='RADIO' id='radio_option' name='radio_option' value='2'>B / 
        <input type='RADIO' id='radio_option' name='radio_option' value='3'>C /
        <input type='RADIO' id='radio_option' name='radio_option' value='4'>D
        <br />
        <input type='HIDDEN' id='txt_search' name='txt_search' value='COMP4632' />
        <input type='SUBMIT' id='submit_vote' name='submit_vote' value='Vote' onclick='javascript: return validateVote();' />
    </form>
</body>
</html>