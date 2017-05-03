<?php

 
//importing required script
require_once '../includes/DbOperation.php';
 
$response = array();
 
if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
    
        //getting values
        $chat_room_id = $_GET['chat_room_id'];
        $user_id = $_GET['user_id'];
        $message = $_GET['message'];
        
        $response['result'] = array();
        
 
        //creating db operation object
        $db = new DbOperation();
        $result = $db->addMessage($user_id, $chat_room_id, $message);
        array_push($response['result'],$result);
        $response['error'] = false;
    $response['message'] = 'success';
      
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}
 

echo json_encode($response);