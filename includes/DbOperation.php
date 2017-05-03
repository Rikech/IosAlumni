<?php
 

class DbOperation
{
    private $conn;
 
    //Constructor
    function __construct()
    {
        require_once dirname(__FILE__) . '/Constants.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
 
    //Function to create a new user
    public function createEvent($Name, $DateEv, $Capacity, $Category, $Description, $Address)
    {
    		$stmt = $this->conn->prepare("INSERT INTO Events (Name, DateEv, Capacity, Category, Description, Address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss",$Name, $DateEv, $Capacity, $Category, $Description, $Address);
            if ($stmt->execute()) {
            	$event_id = $this->conn->insert_id;
            	$basePath = '../resources/images/events/';
            	if(!is_dir($basePath)) {!mkdir($basePath, 0777, true);}
				$file1 = $_FILES['firstImage'];
        		if (is_uploaded_file($file1['tmp_name'])) {
            		$photoPath = $basePath.'Image-'.$event_id.'.jpg';
            		if (move_uploaded_file($file1['tmp_name'], $photoPath)) {
            			$stmt = $this->conn->prepare("UPDATE  Events SET Image = '". $photoPath ."' WHERE Id =".$event_id );
            			if ($stmt->execute()) {return EVENT_CREATED;} else {return EVENT_NOT_CREATED;}
            		}
        
        		}
    		}else {return EVENT_NOT_CREATED;}
    		
      
    }
 
 public function getAllEvents(){
        $stmt = $this->conn->prepare("SELECT * FROM Events ORDER BY Id DESC ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

 public function getAllUsers(){
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY created_at DESC ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

	public function getOtherConnectedUsers($user_id){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id != ? AND status = 1 ORDER BY created_at DESC ");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

     public function addMessage($user_id, $chat_room_id, $message) {
        $response = array();
 
        $stmt = $this->conn->prepare("INSERT INTO messages (chat_room_id, user_id, message) values(?, ?, ?)");
        $stmt->bind_param("sss", $chat_room_id, $user_id, $message);
 
        $result = $stmt->execute();
 
        if ($result) {
            $response['error'] = false;
 
            // get the message
           $message_id = $this->conn->insert_id;
            $stmt = $this->conn->prepare("SELECT message_id, user_id, chat_room_id, message, created_at FROM messages WHERE message_id = ?");
            $stmt->bind_param("i", $message_id);
            if ($stmt->execute()) {
                $stmt->bind_result($message_id, $user_id, $chat_room_id, $message, $created_at);
                $stmt->fetch();
                $tmp = array();
                $tmp['message_id'] = $message_id;
                $tmp['chat_room_id'] = $chat_room_id;
                $tmp['message'] = $message;
                $tmp['created_at'] = $created_at;
                $tmp['user_id'] = $user_id;
                return $tmp;
                //$response['message'] = $tmp;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Failed send message';
        }
 
        return $response;
    }

    /*public function getAllChatrooms() {
        $stmt = $this->conn->prepare("SELECT * FROM chat_rooms");
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }*/

    /*function getChatRoom($chat_room_id) {
        $stmt = $this->conn->prepare("SELECT cr.chat_room_id, cr.name, cr.created_at as chat_room_created_at, u.name as username, c.* FROM chat_rooms cr LEFT JOIN messages c ON c.chat_room_id = cr.chat_room_id LEFT JOIN users u ON u.user_id = c.user_id WHERE cr.chat_room_id = ?");
        $stmt->bind_param("i", $chat_room_id);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }*/
    public function isChatRoomExists($chat_room_id) {
        $stmt = $this->conn->prepare("SELECT * from chat_rooms WHERE chat_room_id = ?");
        $stmt->bind_param("s", $chat_room_id);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
        //return false;
    }
 
 public function createChatRoom($chat_room_id, $name)
    {
            
            $stmt = $this->conn->prepare("INSERT INTO chat_rooms (chat_room_id, name) VALUES (?, ?)");
            $stmt->bind_param("ss",$chat_room_id, $name);
            if ($stmt->execute()) {return chat_room_id;} else {return 0;}
           
    }


 public function getAllMessages($chat_room_id){
        $stmt = $this->conn->prepare("SELECT * FROM messages WHERE chat_room_id = ? ");
        $stmt->bind_param("s", $chat_room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }


	public function getAllRecentMessages($chat_room_id , $id){

        $stmt = $this->conn->prepare("SELECT * FROM messages WHERE chat_room_id = ? AND user_id != ? AND (TIMESTAMPDIFF(SECOND, created_at, NOW()) <= 20 OR created_at IS NULL) GROUP BY message_id ORDER BY created_at");
        $stmt->bind_param("ss", $chat_room_id,$id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;

    	}
}