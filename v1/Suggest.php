<?php

 
//importing required script
require_once '../includes/DbOperation.php';
 
$response = array();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if (!verifyRequiredParams(array('Name', 'DateEv', 'Capacity', 'Category', 'Description', 'Address'))) {
        //getting values
        $Name = $_POST['Name'];
        $DateEv = $_POST['DateEv'];
        $Capacity = $_POST['Capacity'];
        $Category = $_POST['Category'];
        $Description = $_POST['Description'];
        $Address = $_POST['Address'];
 
        //creating db operation object
        $db = new DbOperation();
 
        //adding Event to database
        
        $result = $db->createEvent($Name, $DateEv, $Capacity, $Category, $Description, $Address);
        //making the response accordingly
        if ($result == EVENT_CREATED) {
            $response['error'] = false;
            $response['message'] = 'Event created successfully';
        } elseif ($result == EVENT_NOT_CREATED) {
            $response['error'] = true;
            $response['message'] = 'Some error occurred';
        }elseif ($result == failed) {
            $response['error'] = true;
            $response['message'] = 'in move_uploaded_file';
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Required parameters are missing';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}
 
//function to validate the required parameter in request
function verifyRequiredParams($required_fields)
{
 
    //Getting the request parameters
    $request_params = $_REQUEST;
 
    //Looping through all the parameters
    foreach ($required_fields as $field) {
        //if any requred parameter is missing
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
 
            //returning true;
            return true;
        }
    }
    return false;
}

echo json_encode($response);