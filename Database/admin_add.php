<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Get values for the record to be added if from enduser_add_action.php
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$age = $_POST["age"];
$street = $_POST["street"];
$city = $_POST["city"];
$state = $_POST["state"];
$zip = $_POST["zip"];
// display the insertion form.
echo("
  <form method=\"post\" action=\"admin_add_action.php?sessionid=$sessionid\">
  First name of Student: <input type=\"text\" value = \"$fname\" size=\"15\" maxlength=\"15\" name=\"fname\"> <br /> 
  Last name of Student: <input type=\"text\" value = \"$lname\" size=\"15\" maxlength=\"15\" name=\"lname\"> <br />
  Student Age: <input type=\"text\" value = \"$age\" size=\"2\" maxlength=\"2\" name=\"age\"> <br />
  Street of Student: <input type=\"text\" value = \"$street\" size=\"20\" maxlength=\"20\" name=\"street\"> <br />
  City of Student: <input type=\"text\" value = \"$city\" size=\"15\" maxlength=\"15\" name=\"city\"> <br />
  State of Student: <input type=\"text\" value = \"$state\" size=\"2\" maxlength=\"2\" name=\"state\"> <br />
  Zip of Student: <input type=\"text\" value = \"$zip\" size=\"5\" maxlength=\"5\" name=\"zip\"> <br />
  ");
  
oci_free_statement($cursor);

echo("
  </select>
  <input type=\"submit\" value=\"Add\">
  </form>

  <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");
?>
