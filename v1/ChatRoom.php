<?php

 
//importing required script
require_once '../includes/DbOperation.php';
 
$response = array();
 
if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
    
        //getting values
        $chat_room_id = $_GET['chat_room_id'];
        $name = $_GET['name'];
        
        
 
        //creating db operation object
        $db = new DbOperation();
        $result = $db->isChatRoomExists($chat_room_id);
        if($result){
            $result = $db->getChatRoom($chat_room_id);
            $response['error'] = false;
            $response['message'] = 'chatroom found successfully' ;
            $response['result'] = "found";

        }else{
           $result = $db->createChatRoom($chat_room_id, $name);
           $response['error'] = false;
            $response['message'] = 'chatroom created successfully' ;
            $response['result'] = "created" ;
        }  
 
      
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}
 

echo json_encode($response);