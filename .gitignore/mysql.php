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

    function createUser($conn, $email, $password, $name, $hkid, $phone, $address, $status) {
        $sql = "";
        $sql .= "INSERT INTO User (email, password, name, hkid, phone, address, status)";
        $sql .= "VALUES ('".$email."', '".$password."', '".$name."', '".$hkid."','".$phone."', '".$address."', '".$status."') ";
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
?>