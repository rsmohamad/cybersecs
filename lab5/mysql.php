<?php
    $DB_HOST = "10.10.4.99";
    $DB_PORT = "3306";
    $DB_USER = "usser4632";
    $DB_PASS = "pass4632";
    $DB_NAME = "labdb";

    function dbOpen() {
        global $DB_HOST, $DB_PORT, $DB_USER, $DB_PASS, $DB_NAME;

        $conn = mysql_connect($DB_HOST.":".$DB_PORT, $DB_USER, $DB_PASS);
        if ($conn) {
            mysql_select_db($DB_NAME, $conn);
        }

        return $conn;
    }

    function dbClose($conn) {
        if (isset($conn) && $conn!=FALSE) {
            mysql_close($conn);
        }
    }

    function retrieveUserByEmailPassword($conn, $email, $password) {
        $rows = NULL;
        $sql = "";
        $sql .= "SELECT * ";
        $sql .= "FROM User ";
        $sql .= "WHERE email='".$email."' AND password='".$password."' ";
        $res = mysql_query($sql, $conn);
        if ($res) {
            $rows = array();
            while ($row = mysql_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysql_free_result($res);
        }

        return $rows;
    }

    function retrieveAllUsers($conn) {
        $rows = NULL;
        $sql = "";
        $sql .= "SELECT * ";
        $sql .= "FROM User";
        $res = mysql_query($sql, $conn);
        if ($res) {
            $rows = array();
            while ($row = mysql_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysql_free_result($res);
        }

        return $rows;
    }

    function retrieveUserByEmailHkid($conn, $email, $hkid) {
        $rows = NULL;
        $sql = "";
        $sql .= "SELECT * ";
        $sql .= "FROM User ";
        $sql .= "WHERE email='".$email."' OR hkid='".$hkid."' ";
        $res = mysql_query($sql, $conn);
        if ($res) {
            $rows = array();
            while ($row = mysql_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysql_free_result($res);
        }
        return $rows;
    }

    function createUser($conn, $email, $password, $name, $hkid, $phone, $address, $status, $secret) {
        $sql = "";
        $sql .= "INSERT INTO User (email, password, name, hkid, phone, address, status, secret)";
        $sql .= "VALUES ('".$email."', '".$password."', '".$name."', '".$hkid."','".$phone."', '".$address."', '".$status."', '".$secret."') ";
        $res = mysql_query($sql, $conn);
        return $res;
    }

    function updateUserPassword($conn, $email, $password) {
        $sql = "UPDATE User SET password='".$password."' where email='".$email."'";
        $res = mysql_query($sql, $conn);
        return $res;
    }

    function updateUserPhoneAndAddress($conn, $email, $phone, $address) {
        $sql = "UPDATE User SET phone='".$phone."', address='".$address."' WHERE email='".$email."'";
        $res = mysql_query($sql, $conn);
        return $res;
    }

    function retrieveVoteByTopic($conn, $topic) {
        $sql = "SELECT * from Vote where topic='".$topic."'";
        $res = mysql_query($sql, $conn);
        if ($res) {
            $rows = array();
            while ($row = mysql_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysql_free_result($res);
        }
        return $rows;
    }

    function createVote($conn, $topic, $optionA, $optionB, $optionC, $optionD) {
        $sql = "INSERT INTO Vote (topic, option_a, option_b, option_c, option_d) values('";
        $sql .= $topic."', '".$optionA."', '".$optionB."', '".$optionC."', '".$optionD."')";
        $res = mysql_query($sql, $conn);
        return $res;
    }

    function retrieveUserByEmail($conn, $email) {
        $rows = NULL;
        $sql = "";
        $sql .= "SELECT * ";
        $sql .= "FROM User ";
        $sql .= "WHERE email='".$email."'";
        $res = mysql_query($sql, $conn);
        if ($res) {
            $rows = array();
            while ($row = mysql_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysql_free_result($res);
        }

        return $rows;
    }

    function retrieveVoteByTopicUseridVoteid($conn, $topic, $userId) {
        $rows = NULL;
        $sql = "";
        $sql .= "SELECT * ";
        $sql .= "FROM Vote ";
        $sql .= "WHERE Vote.topic LIKE '%".$topic."%' AND _id NOT IN ( ";
        $sql .= " SELECT vote_id ";
        $sql .= " FROM User_Vote ";
        $sql .= " WHERE user_id=".$userId." ";
        $sql .= ") ";
        $res = mysql_query($sql, $conn);
        if ($res) {
        $rows = array();
        while ($row = mysql_fetch_assoc($res)) {
        $rows[] = $row;
        }
        mysql_free_result($res);
        }
        return $rows;
    }

    function createUserVote($conn, $userId, $voteId, $choice) {
        $sql = "INSERT INTO User_Vote (user_id, vote_id, choice) values ({$userId}, {$voteId}, {$choice})";
        $res = mysql_query($sql, $conn);
        return $res;
    }

    function retrieveUserVoteByEmail($conn, $email) {
        $rows = NULL;
        $sql = "";
        $sql .= "SELECT User_Vote.*, Vote.topic, Vote.option_a, Vote.option_b, Vote.option_c, Vote.option_d ";
        $sql .= "FROM User_Vote, User, Vote ";
        $sql .= "WHERE User_Vote.user_id=User._id AND User_Vote.vote_id=Vote._id AND User.email='".$email."' ";
       
        $res = mysql_query($sql, $conn);
        if ($res) {
            $rows = array();
            while ($row = mysql_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysql_free_result($res);
        }
        return $rows;
    }

    function updateStatusByEmail($conn, $status, $email) {
        $sql = "UPDATE User SET status='".$status."' WHERE email='".$email."'";
        $res = mysql_query($sql, $conn);
        return $res;
    }

    function retrieveUserByMaxId($conn) {
        $sql = "";
        $sql .= "SELECT LPAD(CONVERT(MAX(_id)+1, CHAR(16)), 16, '0') AS max_id ";
        $sql .= "FROM User ";
        $res = mysql_query($sql, $conn);
        if ($res) {
        $rows = array();
        while ($row = mysql_fetch_assoc($res)) {
        $rows[] = $row;
        }
        mysql_free_result($res);
        }
        return $rows;
    }
?>