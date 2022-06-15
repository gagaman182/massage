<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

include 'conn.php';

$sql = "SELECT
	doctor.id,
	doctor.title,
	doctor.leaves,
	dayofweek( now( ) ) AS dayweek,
CASE
	
	WHEN LEAVES = dayofweek( now( ) ) THEN
	'n' ELSE 'y' 
	END AS leaveday 
FROM
	doctor
	LEFT JOIN leave_doctor ON doctor.id = leave_doctor.resourceId 
	where case when leave_doctor.leavedate = DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d')	then 'n' else 'y' end <> 'n'
	
ORDER BY
doctor.id
	
   ";

$return_arr = array();

if ($result = mysqli_query($conn, $sql)) {
  // while ($row = mysqli_fetch_assoc($result)) {

  //   array_push($return_arr, $row);
  // }
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

    $a['id'] = (int)$row['id'];
    $a['title'] = $row['title'];
    $a['leaves'] = $row['leaves'];
    $a['dayweek'] = $row['dayweek'];
    $a['leaveday'] = $row['leaveday'];


    array_push($return_arr, $a);
  }
}


mysqli_close($conn);

echo json_encode($return_arr);