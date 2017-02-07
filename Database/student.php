<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
//$userid =$_GET("userid");

//grab userid from usersession table
$sql = "select userid
  from usersession where sessionid = '$sessionid'";
echo("<center>");
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

$values = oci_fetch_array ($cursor);
$userid = $values[0]; //put userid into variable from usersession table

// Display the query results
echo "<table border=1>";
echo "<tr><th>Student-ID</th> <th>Name</th><th>Age</th><th>Address</th><th>Student Type</th><th>Status</th><th>Update</th>";

//grab information from enduser table
$sql = "select * from enduser where userid = '$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
 
if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

$values = oci_fetch_array($cursor);
$userid = $values[0];
$name = $values[4].' '.$values[5];
$age = $values[3];
$address = $values[7].' '.$values[8].', '.$values[9].' '.$values[10];
if($values[11] == 'U'){
	$stype = "Undergrad";
}
else{
	$stype = "Graduate Student";
}
if($values[6] == '0'){
	$sstatus = "Good";
}
else{
	$sstatus = "Probation";
}

echo("<tr>" . 
  "<td>$userid</td> <td>$name</td> <td>$age</td> <td>$address</td> <td>$stype</td> <td>$sstatus</td>".
  "<td> <A HREF=\"student_update.php?sessionid=$sessionid&userid=$userid\">Change Password</A> </td> ".
  "</tr>");
echo "</table>";
echo("</br>Enroll in new classes <A HREF = \"enrollment.php?sessionid=$sessionid&userid=$userid\">here</A>");
echo "<h4>" . "Classes enrolled in or have already taken" . "</h4>";
echo "<table border=1>";
echo "<tr><th>Course Name</th> <th>Course Number</th><th>Section ID</th><th>Course Description</th><th>Semester</th><th>Credit Hours</th><th>Grade</th>";

//grab section_id information

$sql = "select sec_id from taken natural join coursesection where sid = '$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
//keep fetching rows until they are false
$i = 0;
while (($row = oci_fetch_row($cursor)) != false){
	$sectionid[$i] = $row[0];
	$i++;
}

//find course name, cno, semester, credit hours, grades
for($i = 0; $i < count($sectionid); $i++){
	$sql = "select title, cno, semester_offered, credit_hrs, description, grade
			from taken t join coursesection s on t.sec_id=s.sec_id
			join course on course_no=cno
			where s.sec_id = '$sectionid[$i]' and sid = '$userid'";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
	$values = oci_fetch_array($cursor);
	$ctitle[$i] = $values[0];
	$cno[$i] = $values[1];
	$semester[$i] = $values[2];
	$credhrs[$i] = $values[3];
	$description[$i] = $values[4];
	$grades[$i] = $values[5];
	if($grades[$i] == 4) $grades[$i] = 'A';
	else if ($grades[$i] >= 3) $grades[$i] = 'B';
	else if ($grades[$i] >= 2) $grades[$i] = 'C';
	else if ($grades[$i] >= 1) $grades[$i] = 'D';
	else if ($grades[$i] == NULL) $grades[$i] = 'IP';
	else $grades[$i] = 'F';
	if($grades[$i] == 'F' or $grades[$i] == 'IP') $creditsearned += 0;
	else $creditsearned += 3;
}
//display the table
echo "<tr>";
for($i = 0; $i < count($sectionid); $i++){
	echo ("<tr>".
		"<td>$ctitle[$i]</td> <td>$cno[$i]</td> <td>$sectionid[$i]</td> <td>$description[$i]</td> <td>$semester[$i]</td> <td>$credhrs[$i]</td> <td>$grades[$i]</td>".
		"<td><A HREF=\"class_remove_action.php?sessionid=$sessionid&userid=$userid&cno=$cno[$i]\">Drop Class</A></td>".
		"</tr>");
}
echo "</tr>";
echo "</table>";

//course summary down here
echo "<h4>" . "Total courses taken and hours earned" . "</h4>";
echo "<table border=1>";
echo "<tr><th>Courses Taken</th> <th>Credits Earned</th><th>GPA</th>";

$sql = "select count(*) from taken where sid = '$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$values = oci_fetch_array($cursor);
$takencourses = $values[0];

$sql = "select sum(grade) from taken where sid = '$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$values = oci_fetch_array($cursor);
$gpa = $values[0] / count($sectionid);

echo("<tr>" . 
  "<td>$takencourses</td> <td>$creditsearned</td> <td>$gpa</td>".
  "</tr>");
echo "</table>";
oci_free_statement($cursor);
echo("</br></br>Click <A HREF = \"logout_action.php?sessionid=$sessionid\">here</A> to Logout.</center>");
?>