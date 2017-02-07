<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);
echo("<center>");
//search bar
echo(" 
  <form method=\"post\" action=\"admin.php?sessionid=$sessionid\">
  Student ID: <input type=\"text\" size=\"8\" maxlength=\"8\" name=\"userid\"> 
  First Name: <input type=\"text\" size=\"15\" maxlength=\"15\" name=\"fname\">
  Student Type: <input type=\"text\ size =\"1\" maxlength=\"1\" name=\"type\"> 
  Status: <input type=\"text\ size =\"1\" maxlength=\"1\" name=\"status\">
  <input type=\"submit\" value=\"Search\">
  </form>
  
  <form method=\"post\" action=\"admin_add.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Add A New User\">
  </form>
  <form method=\"post\" action=\"admin_grade_update.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Update Student's Grade\">
  </form>
  ");
  
$whereclause = " 1=1 ";
// Interpret the query requirements
$q_userid = $_POST["userid"];
$q_fname = $_POST["fname"];
$q_cno = $_POST["cno"];
$q_status = $_POST["status"];
$q_type = $_POST["type"];

if (isset($q_userid) and trim($q_userid) != "") { 
  //$whereclause .= " and userid = '$q_userid'"; 
  $whereclause .= " and userid like '%$q_userid%'";
}

if (isset($q_fname) and $q_fname != "") { 
  $whereclause .= " and fname like '%$q_fname%'"; 
}
/*
if (isset($q_cno) and $q_cno != "") { 
  $whereclause .= " and cno like '%$q_cno%'"; 
}
*/
if (isset($q_status) and $q_status != "") { 
  $whereclause .= " and status like '%$q_status%'"; 
}

if (isset($q_type) and $q_type != "") { 
  $whereclause .= " and st_type like '%$q_type%'"; 
}

// Form the query statement and run it.
$sql = "select * from enduser
		where $whereclause";


$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

// Display the query results
echo "<table border=1>";
echo "<tr><th>ID</th> <th>First Name</th> <th>Last Name</th> <th>Password</th> <th>Status</th><th>Usertype</th><th>Update</th><th>Delete</th><th>Reset Password</th>";

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $userid = $values[0];
  $password = $values[1];
  $fname = $values[4];
  $lname = $values[5];
  $status = $values[6];

  if($values[2] == 1 AND $values[12] == 1){
	  $usertype = 'STUDENT ADMIN';
  }
  else if($values[12] == 1){
	  $usertype = 'ADMIN';
  }
  else{
	  $usertype = 'STUDENT';
  }
  if($values[6] == 1){
	  $status = 'Probation';
  }
else if($values[12] = 1 && $values[2] == 0){
	  $status = 'N/A';
  }
  else{
	  $status = 'Good Standing';
  }
  
  echo("<tr>" . 
    "<td>$userid</td><td>$fname</td> <td>$lname</td> <td>$password</td> <td>$status</td> <td>$usertype</td>".
    "<td> <A HREF=\"admin_update.php?sessionid=$sessionid&userid=$userid\">Update</A> </td> ".
    "<td> <A HREF=\"admin_delete.php?sessionid=$sessionid&userid=$userid\">Delete</A> </td> ".
	"<td><center> <A HREF=\"admin_defaultpassword_action.php?sessionid=$sessionid&userid=$userid\">Reset Password</A></center> </td> ".
    "</tr>");
}
oci_free_statement($cursor);

echo "</table>";
echo("
	<form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid&usertype\">
	<input type=\"submit\" value=\"Go Back\">
	</form>
  
	Click <A HREF = \"logout_action.php?sessionid=$sessionid\">here</A> to Logout.</center>
	");
?>
