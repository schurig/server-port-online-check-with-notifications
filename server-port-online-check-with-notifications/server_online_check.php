<?php

/**
 * ate too many cookies while writing this :o
 * 
 * @author Martin Schurig <hello@schurig.pw>
 * @version 0.5
 * @copyright (c) 11-Sep-2013, Martin Schurig
 * @link http://martinschurig.com, Martin Schurig
 * @license http://www.gnu.org/licenses/ GNU General Public License, version 3 (GPL-3.0)
 */


// Server & Port Settings

$server  = 'martinschurig.com'; // change this to the ip or hostname you want to check
$ports   = array(80,443,22,25,993,995); // enter all ports (services) you want you check


class StatusCheck {
    
    // Notification Settings
    
    private $alert_email     = 'ENTER_EMail_Here';
    private $pushover_token  = 'ENTER_Pushover_Token';
    private $pushover_user   = 'ENTER_Pushover_User_Key';
    

    private $server         = false;
    private $ports          = false;
    private $services       = array(
        80      =>  'HTTP', 
        443     =>  'HTTPS',
        21      =>  'FTP',
        22      =>  'SSH',
        20      =>  'FTP',
        25      =>  'SMTP',
        57      =>  'mail',
        110     =>  'POP3',
        115     =>  'SFTP',
        156     =>  'sql',
        220     =>  'IMAP',
        989     =>  'ftps',
        990     =>  'ftps',
        992     =>  'TELNET',
        993     =>  'IMAPS',
        995     =>  'POP3s',
        3306    =>  'MySQL',
        3443224 =>  'tester', // just to test
    );
    
    /**
     * 
     * @param type $server
     * @param type $ports
     * @return boolean
     */

    public function StatusCheck($server = false, $ports = false) {

        if(!$server) {

            echo 'wrong input!';
        }

        $this->server   =   $server;
        $this->ports    =   $ports;

        if(!$this->checkIP()) {

            return $this->notification(array('title' => 'Server Alert', 'message' => $this->server . ': Server down'));
        }

        $this->checkPorts();

        return true;
    }
    
    /**
     * Pings the entered IP / Hostname
     * @param type $step
     * @return boolean
     */

    public function checkIP($step = 1) {  

        $pingresult = exec('/bin/ping -c 1 ' . $this->server, $outcome, $status);

        if ($status == 0) {

            return true;

        }

        if($step == 1) {

            sleep(3);
            return $this->checkIP($step++);
        }

        return false;

    }
    
    /**
     * Pings the entered ports
     * @return boolean
     */

    public function checkPorts() {

        foreach($this->ports as $port) {

            $service = $this->services[$port];

            $res = $this->checkPort($this->server, $port, $service);

            if(!$res) {

                $this->notification(array('title' => 'Server Alert', 'message' => $this->server . ': The service ' . $service . ' (' . $port . ') does not respond'));
            }
        }

        return true;
    }

    public function checkPort($server, $port = 80, $firstTime = true) {

        if(!is_int($port) || empty($server)) {

            return false;
        }

        $check = @fsockopen($server, $port, $errno, $errstr, 2);

            if(!$check) {

                if($firstTime) {

                    sleep(3);
                    return $this->checkPort($server, $port, false);
                }

                return false;
            }

        return true;
    }
    
    /**
     * Sends the notifications
     * @param type $notification
     * @return string
     */

    private function notification($notification = false) {

        if(!$notification) {

            return 'notification error';
        }

        $m_title   = $notification['title'];
        $m_message = $notification['message'];

        
        curl_setopt_array($ch = curl_init(), array(
          CURLOPT_URL => "https://api.pushover.net/1/messages.json",
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_POSTFIELDS => array(
            "token" => $this->pushover_token,
            "user" => $this->pushover_user,
            "title" => $m_title,
            "message" => $m_message,
            "priority" => 1,
          )));
        $res = curl_exec($ch);
        curl_close($ch);
        

        mail($this->alert_email, $m_title, $m_message);

    }
}

$status = new StatusCheck($server, $ports);

// you like it? send me a cookie :)
// you don't? :( ..send me a cookie anyway *___*
?>