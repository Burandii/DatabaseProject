<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
echo("<center>");
$userid = $_GET["userid"];

echo("
	<form method=\"post\" action=\"enroll_multi_action.php?sessionid=$sessionid&userid=$userid\">
	Course Number: <input type=\"text\" value = \"$cid1\" size=\"10\" maxlength=\"10\" name=\"cid1\"> 
	SectionID: <input type=\"text\" value = \"$sectionid1\" size=\"6\" maxlength=\"6\" name=\"sectionid1\"> <br>
	Course Number: <input type=\"text\" value = \"$cid2\" size=\"10\" maxlength=\"10\" name=\"cid2\"> 
	SectionID: <input type=\"text\" value = \"$sectionid2\" size=\"6\" maxlength=\"6\" name=\"sectionid2\"> <br>
	Course Number: <input type=\"text\" value = \"$cid3\" size=\"10\" maxlength=\"10\" name=\"cid3\"> 
	SectionID: <input type=\"text\" value = \"$sectionid3\" size=\"6\" maxlength=\"6\" name=\"sectionid3\"> <br>
	Course Number: <input type=\"text\" value = \"$cid4\" size=\"10\" maxlength=\"10\" name=\"cid4\"> 
	SectionID: <input type=\"text\" value = \"$sectionid4\" size=\"6\" maxlength=\"6\" name=\"sectionid4\"> <br>
	Course Number: <input type=\"text\" value = \"$cid5\" size=\"10\" maxlength=\"10\" name=\"cid5\"> 
	SectionID: <input type=\"text\" value = \"$sectionid5\" size=\"6\" maxlength=\"6\" name=\"sectionid5\"> <br>
	
	<input type=\"submit\" value=\"Add Classes\">
	</form>
	");
echo "<table border=1>";
echo "<tr><th>Course Number</th><th>Section-ID</th><th>Title</th><th>Credits</th><th>Semester</th><th>Time</th><th>Total Seats</th> <th>Seats Left</th>";
//this gives you the rows
$sql = 'select cno from coursesection join course on course_no = cno';
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
for($i = 0; ($row = oci_fetch_row($cursor)) != false; $i++){
	$cno[$i] = $row[0];
	//echo $cno[$i] . '</br>';
}
for($i = 0; $i < sizeof($cno); $i++){
	$sql = "select sec_id, title, credit_hrs, semester_offered, timeslot, capacity, students_enrolled
		from coursesection s join course c 
		on course_no = cno
		where cno = '$cno[$i]'";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
	$values = oci_fetch_array($cursor);
	$secid[$i] = $values[0];
	$title[$i] = $values[1];
	$credithrs[$i] = $values[2];
	$semester[$i] = $values[3];
	$time[$i] = $values[4];
	$capacity[$i] = $values[5];
	$seatsleft[$i] = $capacity[$i]-$values[6];
}
//list the rows for everything
for($i = 0; $i < sizeof($cno); $i++){
	echo ("<tr>".
	"<td>$cno[$i]</td> <td>$secid[$i]</td> <td>$title[$i]</td> <td>$credithrs[$i]</td>
	<td>$semester[$i]</td> <td>$time[$i]</td> <td>$capacity[$i]</td> <td>$seatsleft[$i]</td>".
	"</tr>");
}  
echo "</table>";
echo("
	<form method=\"post\" action=\"enrollment.php?sessionid=$sessionid\">
  	<input type=\"submit\" value=\"Go Back\">
  	</form>
	");
?>