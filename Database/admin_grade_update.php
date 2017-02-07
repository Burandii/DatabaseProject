<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Get values for the record to be added if from enduser_add_action.php
$sid = $_POST["sid"];
$secid = $_POST["secid"];
$grade = $_POST["grade"];
// display the insertion form.
echo("
  <form method=\"post\" action=\"admin_grade_update_action.php?sessionid=$sessionid\">
  Student ID: <input type=\"text\" value = \"$sid\" size=\"8\" maxlength=\"8\" name=\"sid\"> <br /> 
  Section ID: <input type=\"text\" value = \"$secid\" size=\"10\" maxlength=\"10\" name=\"secid\"> <br />
  New Grade: <input type=\"text\" value = \"$grade\" size=\"3\" maxlength=\"3\" name=\"grade\"> <br />
  ");
  
oci_free_statement($cursor);

echo("
  </select>
  <input type=\"submit\" value=\"Update\">
  </form>

  <form method=\"post\" action=\"admin_grade_update_action.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");
?>
