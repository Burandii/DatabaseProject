<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


ini_set( "display_errors", 0);  


$userid = $_POST["userid"];

// Form the sql string and execute it.
$sql = "delete from enduser where userid = '$userid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
 // echo "<B>Student is enrolled, cannot delete.</B> <BR />";


  die("<B>Student is enrolled, cannot delete.</B> <BR />
  ");
}

// Record deleted.  Go back.
Header("Location:admin.php?sessionid=$sessionid");
?>
