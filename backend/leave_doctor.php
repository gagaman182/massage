<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');

include 'conn.php';

$sql = "SELECT
	leave_doctor.id,

	doctor.id as resourceId,
	doctor.title,
  leave_doctor.leavedate
--  date_format(leave_doctor.leavedate,'%d-%m-%Y') as leavedate
FROM
	leave_doctor
	LEFT JOIN doctor ON leave_doctor.resourceId = doctor.id
	ORDER BY leave_doctor.id desc";

$return_arr = array();

if ($result = mysqli_query($conn, $sql)) {
  while ($row = mysqli_fetch_assoc($result)) {

    array_push($return_arr, $row);
  }
}

mysqli_close($conn);

echo json_encode($return_arr);