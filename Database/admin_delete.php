<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$q_userid = $_GET["userid"];
echo("<center>");

// Fetech the record to be deleted and display it
$sql = "select userid, password from enduser where userid = '$q_userid'";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if (!($values = oci_fetch_array ($cursor))) {
  // Record already deleted by a separate session.  Go back.
  Header("Location:admin.php?sessionid=$sessionid");
}
oci_free_statement($cursor);

$userid = $values[0];
$password = $values[1];

// Display the record to be deleted.
echo("
  <form method=\"post\" action=\"admin_delete_action.php?sessionid=$sessionid\">
  Id (Read-only): <input type=\"text\" readonly value = \"$userid\" size=\"10\" maxlength=\"10\" name=\"userid\"> <br /> 
  Password: <input type=\"text\" disabled value = \"$password\" size=\"20\" maxlength=\"30\" name=\"password\">  <br />
  ");

oci_free_statement($cursor);

echo("
  </select>  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </center>
  </form>
  ");

?>
