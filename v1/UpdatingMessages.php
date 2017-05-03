<?php

require_once '../includes/DbOperation.php';
 
$response = array();
 
if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
 
        $chat_room_id = $_GET['chat_room_id'];
        $id = $_GET['id'];
        $db = new DbOperation();
        //$messages = $db->getAllMessages($chat_room_id);
        $response['messages'] = array();
        $messages = $db->getAllRecentMessages($chat_room_id,$id);
        

        while($message = $messages->fetch_assoc()){
                            $temp = array();
 
                            //inserting the event in the temporary array
                            $temp['message_id'] = $message['message_id'];
                            $temp['chat_room_id']=$message['chat_room_id'];
                            $temp['user_id']=$message['user_id'];
                            $temp['message']=$message['message'];
                            $temp['created_at']=$message['created_at'];
    
                            array_push($response['messages'],$temp);
                }
                            
}else {
     $response['error'] = true;
    $response['message'] = 'Invalid request';
}
 
//displaying the array in json format
echo json_encode($response);