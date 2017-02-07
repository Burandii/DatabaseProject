<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
echo("<center>");
$userid = $_GET["userid"];
$search = $_POST["search"];
$search = '%' . $search . '%';

$sql = "select cno from course 
		where cno like '$search'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
for($i = 0; ($row = oci_fetch_row($cursor)) != false; $i++){
	$cno[$i] = $row[0];
	//echo $cno[$i] . '</br>';
}
echo "<table border=1>";
echo "<tr><th>Course Number</th> <th>Section-ID</th> <th>Title</th><th>Credits</th><th>Semester</th><th>Time</th><th>Total Seats</th> <th>Seats Left</th>";

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
for($i = 0; $i < sizeof($cno); $i++){
	echo ("<tr>".
	"<td>$cno[$i]</td> <td>$secid[$i]</td> <td>$title[$i]</td> <td>$credithrs[$i]</td>
	<td>$semester[$i]</td> <td>$time[$i]</td> <td>$capacity[$i]</td> <td>$seatsleft[$i]</td>".
	"<td> <A HREF=\"enroll_action.php?sessionid=$sessionid&userid=$userid&cno=$cno[$i]&secid=$secid[$i]\">Enroll</A> </td> ".
	//"<td><input type=checkbox value=Enroll name=$ch[$i]/> </td>".
	"</tr>");
}
echo "</table>";
echo("  
  <form method=\"post\" action=\"student.php?sessionid=$sessionid&usertype=$usertype\">
  <input type=\"submit\" value=\"Home\">
  </center>
  </form>
  ");
?>
