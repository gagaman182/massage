<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

include 'conn.php';

$sql = "SELECT * from eventpt where state = '1' and  DATE_FORMAT(`start` , '%Y-%m-%d') >=  CURDATE() 
   ";

$return_arr = array();

if ($result = mysqli_query($conn, $sql)) {
  while ($row = mysqli_fetch_array($result)) {

    //array_push($return_arr, $row);


    $a['id'] = $row[0];
    $a['resourceId'] = $row[1];
    $a['start'] = $row[2];
    $a['end'] = $row[3];
    // $a['title'] = 'จองแล้ว';
    $a['name'] = $row[5];
    $a['constraint'] = $row[6];
    $a['user'] = $row[7];
    $a['username'] = $row[8];
    // $a['color'] = $row[17];
    if ($row[17] == '#A149FA') {
      $a['color'] = '#47B5FF';
      $a['textColor'] = '#F9F2ED';
      $a['borderColor'] = '#D6D5A8';
      $a['title'] = $row[10];
    } else {
      $a['color'] = '#FFB562';
      $a['textColor'] = '#51557E';
      $a['borderColor'] = '#D6D5A8';
      $a['title'] = 'จองแล้ว';
    }





    array_push($return_arr, $a);
  }
}

mysqli_close($conn);

echo json_encode($return_arr);