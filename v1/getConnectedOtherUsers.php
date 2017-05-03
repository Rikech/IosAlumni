<?php

require_once '../includes/DbOperation.php';
 
$response = array();
 
if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
        //getting values
        $id = $_GET['user_id'];
        
        //creating db operation object
        $db = new DbOperation();
 
        //adding Event to database
        
        $users = $db->getOtherConnectedUsers($id);
        $response['users'] = array();

        while($user = $users->fetch_assoc()){
    //creating a temporary array
    $temp = array();
 
    //inserting the event in the temporary array
    $temp['user_id'] = $user['user_id'];
    $temp['name']=$user['name'];
    $temp['email']=$user['email'];
    $temp['created_at']=$user['created_at'];
    $temp['status']=$user['status'];
    
    array_push($response['users'],$temp);
}
}else {
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}
 
//displaying the array in json format
echo json_encode($response);