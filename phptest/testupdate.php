<?php

 $conn;
 
require_once '../includes/Constants.php';
require_once '../includes/DbConnect.php';
        
$db = new DbConnect();
$conn = $db->connect();
$photoPath = "test update4";
$Name = 'Name';
 $stmt = $conn->prepare('UPDATE Events SET Image=? WHERE Name=?');
 $stmt->bind_param("ss",$photoPath,$Name);
 $stmt->execute();


