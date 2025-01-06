<?php
class User
{
    public static function chechLoginState($pdo, $userid, $token, $serial)
    {
        $sql = 'SELECT * FROM user_sessions WHERE session_userid=:userid AND session_token=:token AND session_serial=:session_serial';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid, 'token' => $token, 'session_serial' => $serial]);
        $result = $stmt->rowCount();
        if ($result == 1) {
            return true;
        }
    }
    public static function createRecord($pdo, $userid)
    {
        $sql = "DELETE FROM user_sessions WHERE session_userid=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid]);
        $token = User::createString(30);
        $serial = User::createString(30);
        // func::createCookie($user_name,$user_id,$token,$serial);
        User::createSession($pdo, $userid, $token, $serial);
        date_default_timezone_set('Asia/Colombo');
        $date = date('d M Y @ H:i:s');
        $sql = "INSERT INTO user_sessions (session_userid,session_token,session_serial,session_date) VALUES (:userid,:token,:session_serial,:session_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid, 'token' => $token, 'session_serial' => $serial, 'session_date' => $date]);
    }
    public static function createSession($pdo, $userid, $token, $serial)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['userid'] = $userid;
        $_SESSION['token'] = $token;
        $_SESSION['serial'] = $serial;
        $sql = "SELECT user_role,user_office FROM user_infor WHERE user_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid]);
        $result = $stmt->fetchall();
        if (!empty($result)) {
            foreach ($result as $row) {
                $_SESSION['role'] = $row['user_role'];
                $_SESSION['office'] = $row['user_office'];
            }
        }
    }
    public static function createString($len)
    {
        $string = "1hjuakhyejNSTWEL1MJGhawioq19mjndiyranwmlYWKMNVZGQEhjku65ghjrs";
        $s = '';
        $r_new = '';
        $r_old = '';
        for ($i = 1; $i < $len; $i++) {
            while ($r_old == $r_new) {
                $r_new = rand(0, 60);
            }
            $r_old = $r_new;
            $s = $s . $string[$r_new];
        }
        return $s;
    }
    public static function addRecord($pdo, $userid, $username, $action)
    {
        $location = gethostname();
        $ipaddress = "192.168.1.1";
        date_default_timezone_set('Asia/Colombo');
        // Prints something like: Monday 8th of August 2005 03:12:46 PM
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $sql = "INSERT INTO user_activity (activity_userid,activity_username,activity, activity_date,activity_time,activity_ipaddres,activity_worklocation) VALUES (:userid, :username,:action,:action_date,:action_time,:ipaddress,:work_location)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid, 'username' => $username, 'action' => $action, 'action_date' => $date, 'action_time' => $time, 'ipaddress' => $ipaddress, 'work_location' => $location]);
    }
    public static function getUserinfor($pdo, $userid)
    {
        $sql = "SELECT * FROM user_infor WHERE 	user_id=:userid ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid]);
        $result = $stmt->fetchall();
        if (!empty($result)) {
            foreach ($result as $row) {
                $username = $row['user_fname'];
                $user_role = $row['user_role'];
                $userimage = $row['user_image'];
            }
            switch ($user_role) {
                case 1:
                    $usertype = 'Regional Manager';
                    break;
                case 2:
                    $usertype = 'Helpdesk Officer';
                    break;
                case 3:
                    $usertype = 'System Administrator';
                    break;
                case 4:
                    $usertype = 'IT Officer';
                    break;
                case 5:
                    $usertype = 'DGM Finance';
                    break;
                case 6:
                    $usertype = 'General Manager';
                    break;
                case 7:
                    $usertype = 'Employee';
                    break;
                case 8:
                    $usertype = 'DGM Admin';
                    break;
                case 9:
                    $usertype = 'Procurement Manager';
                    break;
                case 10:
                    $usertype = 'Cheque Writer';
                    break;
            }
            return array($username, $usertype, $userimage);
        }
    }
    public static function pureNmber($number)
    {
        $numlen = strlen($number);
        switch ($numlen) {
            case 1:
                $n = "00000" . $number;
                break;
            case 2:
                $n = "0000" . $number;
                break;
            case 3:
                $n = "000" . $number;
                break;
            case 4:
                $n = "00" . $number;
                break;
            case 5:
                $n = "0" . $number;
                break;
            case 6:
                $n = $number;
                break;
        }
        return $n;
    }
    public static function tracker($pdo, $tracker, $action, $userid, $traoffice, $actid, $atable)
    {
        //-----------------date----------------------
        date_default_timezone_set('Asia/Colombo');
        $date = date('Y-m-d');
        $time = date('H:i:s');
        //------------------------------------------
        $sql = "INSERT INTO $tracker (tracker_userid,tracker_action,tracker_office,tracker_date,tracker_time,tracker_actid,tracker_table) VALUES (:userid,:act,:traoffice,:actdate,:acttime,:actid,:atable)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid, 'act' => $action, 'traoffice' => $traoffice, 'actdate' => $date, 'acttime' => $time, 'actid' => $actid, 'atable' => $atable]);
    }
}
