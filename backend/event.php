<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

include 'conn.php';

$sql = "SELECT * from eventpt where state = '1' and  DATE_FORMAT(`start` , '%Y-%m-%d') >=  CURDATE() 
   ";

$return_arr = array();

if ($result = mysqli_query($conn, $sql)) {
  while ($row = mysqli_fetch_assoc($result)) {

    array_push($return_arr, $row);
  }
}

mysqli_close($conn);

echo json_encode($return_arr);