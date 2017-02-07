<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
echo("<center>");
$userid = $_GET["userid"];
$cno = $_GET["cno"];
//get sec_id
$sql = "select sec_id from coursesection where course_no = '$cno'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$value = oci_fetch_array($cursor);
$secid = $value[0];
//check if enrolldate has ended, if it's ended you can no longer drop it
$sql = "select enroll_deadline from coursesection where course_no = '$cno'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$value = oci_fetch_array($cursor);
$deadline = $value[0];
//get today's date
$sql = "select to_char (sysdate) \"now\" from dual";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$value = oci_fetch_array($cursor);
$today = $value[0];

if($today > $deadline){
	die("Deadline to drop class is over");
}
else{
	$sql = "delete from taken
			where sid='$userid' and sec_id='$secid'";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
}
//change seat count
$sql = "update coursesection
		set students_enrolled = (students_enrolled - 1)
		where sec_id = '$secid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
//update student status
$sql = "select SUM(grade) from taken where sid = '$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$sum = oci_fetch_array($cursor);
if($sum[0] == NULL){
	$sum[0] = 0;
}

$sql = "select count(*) from taken where sid = '$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$total = oci_fetch_array($cursor);
if($total[0] == 0){
	$total[0] = 1;
}

if($sum[0]/$total[0] < 2.0){//put on probation
	$sql = "update enduser set status = '1' where userid = '$sid'";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
}
else{
	$sql = "update enduser set status = '0' where userid = '$sid'";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
}
oci_free_statement($cursor);
echo "Class successfully dropped";
echo
  ("
  <form method=\"post\" action=\"student.php?sessionid=$sessionid&userid=$userid\">
  <input type=\"submit\" value=\"Go Back\">
  </center>
  </form>
  ");
?>
