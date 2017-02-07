<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
echo("<center>");
// Verify where we are from, admin.php or  admin_update_action.php.
if (!isset($_POST["update_fail"])) { // from admin.php
  // Fetch the record to be updated.
  $userid = $_GET["userid"];

  // the sql string
  $sql = "select userid, password, student_flag, admin_flag from enduser where userid = '$userid'";
  //echo($sql);

  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($result == false){
    display_oracle_error_message($cursor);
    die("Query Failed.");
  }

  $values = oci_fetch_array ($cursor);
  oci_free_statement($cursor);

  $userid = $values[0];
  $password = $values[1];
  if($values[2] == 1 AND $values[3] == 1){
	  $usertype = 'STUDENT ADMIN';
  }
  else if($values[3] == 1){
	  $usertype = 'ADMIN';
  }
  else{
	  $usertype = 'STUDENT';
  }
}
else { // from admin_update_action.php
  // Obtain values of the record to be updated directly.
  $userid = $_POST["userid"];
  $password = $_POST["password"];
  $usertype = $_POST["usertype"];
}

// Display the record to be updated.
echo("

  <form method=\"post\" action=\"admin_update_action.php?sessionid=$sessionid\">
  UserId: <input type=\"text\" readonly value = \"$userid\" size=\"8\" maxlength=\"8\" name=\"userid\"> <br /> 
  Password (Required): <input type=\"text\" value = \"$password\" size=\"12\" maxlength=\"12\" name=\"password\">  <br />
  ");

//dropdown list
echo("
  User Type (Required):
  <select name=\"usertype\">
  <option value=\"usertype\" disabled>$usertype:</option>
  <option value=\"student\">STUDENT</option>
  <option value=\"admin\">ADMIN</option>
  <option value=\"sadmin\">STUDENT ADMIN</option>
  ");


echo("
  </select>  </br><input type=\"submit\" value=\"Update\">  
  </form>
  
  <form method=\"post\" action=\"admin.php?sessionid=$sessionid&usertype=$usertype\">
  <input type=\"submit\" value=\"Go Back\">
  </center>
  </form>
  ");
?>
