<?php

/**
 * 
 */

/* Namespace */

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat_roll implements MessageComponentInterface
{
	
	public $CI;

	function __construct(){
		$this->CI =& get_instance();
	}

	/* All RatChet functions */

	public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->CI->clients->attach($conn);

        //echo "New connection! ({$conn->resourceId})\n";
        echo "Server Started";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->CI->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $message_data = json_decode($msg,true);

        $user_id = $message_data['user_id']??'';
        $text_msg= $message_data['message']??'';

        if($user_id && $text_msg!=''){

        	$this->CI->db->insert('message',['user_id' => $user_id,'text' => $text_msg,'added_on' => date('Y-m-d h:i:s')]);

        	$msg_id = $this->CI->db->insert_id();

        	if($msg_id){

	        	$this->CI->db->where('message_id',$msg_id);
	        	$this->CI->db->join('users AS us','message.user_id = us.id');
	        	$this->CI->db->select('user_id,text,added_on,name');
	        	$current_data = $this->CI->db->get('message')->row();
	        	if(!empty($current_data)){
			        foreach ($this->CI->clients as $client) {
			            if ($from !== $client) {
			                // The sender is not the receiver, send to each client connected
			                $client->send(json_encode($current_data));
			            }
			        }
	        	}
        	}
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->CI->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}