<?php
header('Content-Type:application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization');
$data = json_decode(file_get_contents('php://input'), true);
include 'conn.php';

// $epidem_report_quid = '55125500';
$starttime = $data['starttime'];
$endtime = $data['endtime'];
$id = $data['id'];
$state = $data['state'];





$sql = "SELECT * from eventpt where (start = '" . $starttime . "' or end = '" . $endtime . "')  and resourceId = '" . $id . "' and state = '" . $state . "'
      ";


$return_arr = array();

if ($result = mysqli_query($conn, $sql)) {
  while ($row = mysqli_fetch_assoc($result)) {

    array_push($return_arr, $row);
  }
}

mysqli_close($conn);

echo json_encode($return_arr);