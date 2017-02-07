<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
echo("<center>");
// Verify how we reach here
if (!isset($_POST["update_fail"])) { // from welceomepage.php
  // Get the userid, fetch the record to be updated from the database 
  $userid = $_GET["userid"];

  // the sql string
  $sql = "select userid, password from enduser where userid = '$userid'";
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
}
else { // from update_action.php
  // Get the values of the record to be updated directly
  $userid = $_POST["userid"];
  $password = $_POST["password"];
}

// display the record to be updated.  
echo("
  <form method=\"post\" action=\"student_update_action.php?sessionid=$sessionid\">
  UserId (Read-only): <input type=\"text\" readonly value = \"$userid\" size=\"8\" maxlength=\"8\" name=\"userid\"> <br /> 
  Password (Required): <input type=\"text\" value = \"$password\" size=\"12\" maxlength=\"12\" name=\"password\">  <br />
");

echo("
  <form method=\"post\" action=\"student.php?sessionid=$sessionid\">
  </select>  <input type=\"submit\" value=\"Update\">

  </form>

  <form method=\"post\" action=\"student.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </center>
  </form>
  ");
?>