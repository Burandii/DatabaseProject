<?
include "utility_functions.php";
$connection = oci_connect ("gq006", "djptas", "gqiannew2:1521/pdborcl");
$sessionid =$_GET["sessionid"];
verify_session($sessionid);
echo("<center>");
$userid = $_GET["userid"];

$cid1=$_POST["cid1"];
$sectionid1=$_POST["sectionid1"];
$cid2=$_POST["cid2"];
$sectionid2=$_POST["sectionid2"];
$cid3=$_POST["cid3"];
$sectionid3=$_POST["sectionid3"];
$cid4=$_POST["cid4"];
$sectionid4=$_POST["sectionid4"];
$cid5=$_POST["cid5"];
$sectionid5=$_POST["sectionid5"];

//-----------------------------------------------------
//check if class if full
for($i = 1; $i < 6; $i++){
	$realsecid = 'sectionid'.$i;	
	$secid = $_POST["$realsecid"];
	if($secid != NULL){
		$sql = "select capacity, students_enrolled from coursesection where sec_id='$secid'";
		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];
		$cursor = $result_array["cursor"];
		$values = oci_fetch_array($cursor);

		if($values[0] - $values[1] == 0){
			die("A class is full,try again");
		//break here
		}
	}	
}

//-----------------------------------------------------
//check if deadline has passed
for($i = 1; $i < 6; $i++){
	$realsecid = 'sectionid'.$i;	
	$secid = $_POST["$realsecid"];
	if($secid != NULL){
		$sql = "begin
				check_deadline(:secid, :error);
				end;";
		$cursor = oci_parse($connection, $sql);
		if($cursor == false){
			echo oci_error($connection)."<br>";
			exit;
		}
		oci_bind_by_name($cursor, ":error", &$error, 100);
		oci_bind_by_name($cursor, ":secid", &$secid, 8);
		$result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
		if($result == false){
			display_oracle_error_message($cursor);
			echo oci_error($cursor)."<BR>";
			exit;
		}
	
		if($error == 0){//passed the deadline
			die("Class is no longer open, try another");
			//break
		}
	}	
}

//-----------------------------------------------------
//check if prereq is fulfilled
//grab prereq for class we wanna enroll in
for($i = 1; $i < 6; $i++){
	$realsecid = 'sectionid'.$i;	
	$secid = $_POST["$realsecid"];
	$realcno = 'cid'.$i;	
	$cno = $_POST["$realcno"];

	$sql = "select prereq_needed from course where cno = '$cno'";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];

	if($result == false){
		echo "stuff";
	}
	$values = oci_fetch_array($cursor);
	$prereq = $values[0];
	if ($prereq != $cno){ //doesn't have one
		$sql = "select course_no from taken natural join coursesection where sid = '$userid'";
		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];
		$cursor = $result_array["cursor"];
		while (($row = oci_fetch_row($cursor)) != false){
			if($row[0] == $prereq){
				$taken_prereq = 1;
				break;
				//can enroll
			}
			else{
				$taken_prereq = 0;
			}
		}
		echo $taken_prereq;
		if($taken_prereq != 1){
			//student has not fulfilled prereq
			die("You must take required course first");
		}
	}
}

//now see if student had taken the class
//-----------------------------------------------------
for($i = 1; $i < 6; $i++){
	$realsecid = 'sectionid'.$i;	
	$secid = $_POST["$realsecid"];
	$realcno = 'cid'.$i;	
	$cno = $_POST["$realcno"];
	$sql = "select enroll_flag 
			from taken natural join coursesection
			where sid = '$userid' and course_no = '$cno'";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
	$values = oci_fetch_array($cursor);
	if($values[0] == 1){ //they have taken the class check for passing grade
		$sql = "select grade 
				from taken natural join coursesection
				where sid = '$userid' and course_no = '$cno'";
		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];
		$cursor = $result_array["cursor"];
		$values = oci_fetch_array($cursor);
		if($values[0] >2){ //their grade is good enough, they don't need to retake
			die("You've taken this class and gotten a passing grade");
		} 
	}
	if($secid != NULL)
	{
		$sql = "insert into taken values('$userid', '$secid', '', '1')";
		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];
		$cursor = $result_array["cursor"];
		//update seats
		$sql = "update coursesection
				set students_enrolled = (students_enrolled + 1)
				where sec_id = '$secid'";
		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];
		$cursor = $result_array["cursor"];
	}
	
}

//Done Checking
oci_close($connection);
oci_free_statement($cursor);
echo "</br>You have successfully enrolled in your classes";
echo("  
  <form method=\"post\" action=\"student.php?sessionid=$sessionid&userid=$userid\">
  <input type=\"submit\" value=\"Go Back\">
  </center>
  </form>
  ");
?>
