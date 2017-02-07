<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Suppress PHP auto warning.
ini_set( "display_errors", 0);  

// Obtain information for the record to be updated.
$userid = $_POST["userid"];
$password = $_POST["password"];
$usertype = $_POST["usertype"];

if($usertype == NULL or $usertype == 'student')  $usertype = 'STUDENT';
else if($usertype == 'admin') $usertype = 'ADMIN';
else $usertype = 'STUDENT ADMIN';

if ($userid == ""){ $userid = "NULL"; }

// Form the sql string and execute it.
if($usertype == 'ADMIN'){
	$sql = "update enduser set password = '$password', student_flag = '0', admin_flag = '1' where userid = '$userid'";
}
else if($usertype == 'STUDENT'){
	$sql = "update enduser set password = '$password', student_flag = '1', admin_flag = '0' where userid = '$userid'";
}
else{
	$sql = "update enduser set password = '$password', student_flag = '1', admin_flag = '1' where userid = '$userid'";
}

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";

  display_oracle_error_message($cursor);

  die("<i> 

  <form method=\"post\" action=\"admin_update?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"1\" name=\"update_fail\">
  <input type=\"hidden\" value = \"$userid\" name=\"userid\">
 
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record updated.  Go back.
Header("Location:admin.php?sessionid=$sessionid");
?>
