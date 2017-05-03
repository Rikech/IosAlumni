<?php
 
//including the file dboperation
require_once '../includes/DbOperation.php';
 
//creating a response array to store data
$response = array();
 
//creating a key in the response array to insert values
//this key will store an array iteself
$response['users'] = array();
 
//creating object of class DbOperation
$db = new DbOperation();
 
//getting the events using the function we created
$users = $db->getAllUsers();
 
//looping through all the events.
while($user = $users->fetch_assoc()){
    //creating a temporary array
    $temp = array();
 
    //inserting the event in the temporary array
    $temp['user_id'] = $user['user_id'];
    $temp['name']=$user['name'];
    $temp['email']=$user['email'];
    $temp['created_at']=$user['created_at'];
    
    array_push($response['users'],$temp);
}
 
//displaying the array in json format
echo json_encode($response);