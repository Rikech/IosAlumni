<?php
 
//including the file dboperation
require_once '../includes/DbOperation.php';
 
//creating a response array to store data
$response = array();
 
//creating a key in the response array to insert values
//this key will store an array iteself
$response['Events'] = array();
 
//creating object of class DbOperation
$db = new DbOperation();
 
//getting the events using the function we created
$events = $db->getAllEvents();
 
//looping through all the events.
while($event = $events->fetch_assoc()){
    //creating a temporary array
    $temp = array();
 
    //inserting the event in the temporary array
    $temp['Id'] = $event['Id'];
    $temp['Name']=$event['Name'];
    $temp['DateEv']=$event['DateEv'];
    $temp['Capacity']=$event['Capacity'];
    $temp['Category']=$event['Category'];
    $temp['Description']=$event['Description'];
    $temp['Address']=$event['Address'];
    $temp['Image']=$event['Image'];
    //inserting the temporary array inside response
    array_push($response['Events'],$temp);
}
 
//displaying the array in json format
echo json_encode($response);