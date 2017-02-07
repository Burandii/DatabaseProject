<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
echo("<center>");
$userid = $_GET["userid"];
//search bar
echo("
  <form method=\"post\" action=\"section_search.php?sessionid=$sessionid&userid=$userid\">
  <input id=\"search\" type=\"text\" placeholder=\"Search using course num\" name=\"search\">
  <input id=\"submit\" type=\"submit\" value=\"Search\">
  </form>
  ");
//list sections button
echo ("
  <form method=\"post\" action=\"section_list.php?sessionid=$sessionid&userid=$userid\">
  <input type=\"submit\" value=\"List All Sections\">
  </form>
  ");
//enroll multiple
echo ("
  <form method=\"post\" action=\"enroll_multiple.php?sessionid=$sessionid&userid=$userid\">
  <input type=\"submit\" value=\"Enroll in multiple classes at once.\">
  </form>
  ");
//go back button
echo("  
  <form method=\"post\" action=\"student.php?sessionid=$sessionid&userid=$userid\">
  <input type=\"submit\" value=\"Go Back\">
  </center>
  </form>
  ");
?>
