<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Server Running";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        $numRecv = count($this->clients) - 1;

        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $message_data = json_decode($msg,true);

        $user_id = $message_data['user_id']??'';
        $text_msg= $message_data['message']??'';
        $token   = $message_data['token']??'';

        $valid   = $token !='' ? $this->verify_token($token) : false;

        if($valid){

            $sql  = "INSERT INTO `message` (`message_id`, `text`, `user_id`, `added_on`) VALUES (NULL,'".$text_msg."', ".$user_id.", '".date('Y-m-d h:i:s')."')";

            $conn = $this->mysql_connect();

            $info = $conn->query($sql);

            if($info){

                $message_id = $conn->insert_id??'';

                $ms = $conn->query("SELECT user_id as id,name,text,DATE_FORMAT(added_on,'%h %i') AS added_on FROM `message` JOIN users as us on message.user_id = us.id where message.message_id = ".$message_id."");

                if($ms->num_rows??false){

                    $msg = $ms->fetch_assoc();
                    
                    foreach ($this->clients as $client) {
                        if ($from !== $client) {
                            // The sender is not the receiver, send to each client connected
                            $client->send(json_encode($msg));
                        }
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    private function mysql_connect(){

        $host = 'localhost';
        $user = 'root';
        $pass = 'sd';
        $db   = 'chat';

        return  new mysqli($host,$user,$pass,$db);
    }

    private function verify_token($token){

        $conn = $this->mysql_connect();

        $info = $conn->query('SELECT * FROM users where token = "'.$token.'" ');

        if($info->num_rows??false){

            return true;
        }

        return false;
    }


}