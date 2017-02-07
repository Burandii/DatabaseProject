<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

echo("<center>");

// Here we can generate the content of the welcome page
echo("Data Management Menu: <br />");
echo("<UL>
  <A HREF=\"student.php?sessionid=$sessionid\">Student</A><BR>
  <A HREF=\"admin.php?sessionid=$sessionid\">Adminstator</A>
  </UL>");

echo("<br />");

echo("Click <A HREF = \"logout_action.php?sessionid=$sessionid\">here</A> to Logout.</center>");
?>