<?php
header('Content-Type:application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization');
$data = json_decode(file_get_contents('php://input'), true);

$title = $data['hn'];
$start = $data['start'];
$end = $data['end'];
$resourceid = $data['resourceid'];
$user = 'online';
$hn = $data['hn'];
$idcard = $data['idcard'];
$color = '#A149FA';
$state = '1';

// $title = '1-49 test';
// $start = '2022-06-30 16:40:00';
// $end = '2022-06-30 17:50:00';
// $resourceid = '34';
// $user = 'online';
// $hn = '1-49 test';
// $idcard = '3900000000000';

// $color = '#A149FA';


// CURRENT_TIMESTAMP
include 'conn.php';

if (!empty($resourceid)) {



  $strvisit  = "  INSERT INTO eventpt(title,start,end,resourceid,hn,idcard,state,userdate,color)
 
      VALUES('" . $title . "','" . $start . "','" . $end . "',
      '" . $resourceid . "','" . $hn . "','" . $idcard . "',
       '" . $state . "',CURRENT_TIMESTAMP,'" . $color . "')";



  if ($conn->query($strvisit) === TRUE) {


    $return_message = array();
    $row_array['message'] = "เพิ่มข้อมูลสำเร็จ";
    array_push($return_message, $row_array);
  } else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
    $return_message = array();
    $row_array['message'] = "เพิ่มข้อมูลไม่สำเร็จ";
    array_push($return_message, $row_array);
  }
}

mysqli_close($conn);

echo json_encode($return_message);