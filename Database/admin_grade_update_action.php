<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
ini_set( "display_errors", 0);

$sid = trim($_POST["sid"]);
$secid = trim($_POST["secid"]);
$grade = trim($_POST["grade"]);
$secid = strtoupper($secid);

$sql = "update taken set grade = '$grade' where sid = '$sid' and sec_id = '$secid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";

  display_oracle_error_message($cursor);

  die("<i> 

  <form method=\"post\" action=\"admin_grade_update?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"1\" name=\"update_fail\">
  <input type=\"hidden\" value = \"$userid\" name=\"userid\">
 
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}
//change student's status accordingly to new grade
$sql = "select SUM(grade) from taken where sid = '$sid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$sum = oci_fetch_array($cursor);
$sql = "select count(*) from taken where sid = '$sid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$total = oci_fetch_array($cursor);

if($sum[0]/$total[0] < 2.0){
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
// Record updated.  Go back.
Header("Location:admin.php?sessionid=$sessionid");
?>
