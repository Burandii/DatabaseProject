<?
include "utility_functions.php";
$connection = oci_connect ("gq006", "djptas", "gqiannew2:1521/pdborcl");
$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Suppress PHP auto warnings.
ini_set( "display_errors", 0);  

// Get the values of the record to be inserted.
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$age = trim($_POST["age"]);
$street = trim($_POST["street"]);
$city = trim($_POST["city"]);
$state = trim($_POST["state"]);
$zip = trim($_POST["zip"]);
$password = "bronchos";
$finitial = substr($fname, 0, 1);
$finitial = $finitial;
$linitial = substr($lname, 0, 1);
$linitial = $linitial;

//user procedure to add into db
$sql = "begin
		new_student_id(:fname,:lname);
		end;";
$cursor = oci_parse($connection, $sql);
if($cursor == false){
	echo oci_error($connection)."<br>";
	exit;
}

oci_bind_by_name($cursor, ":lname", &$lname, 15);
oci_bind_by_name($cursor, ":fname", &$fname, 15);
$result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);

if($result == false){
	display_oracle_error_message($cursor);
	echo oci_error($cursor)."<BR>";
	exit;
}
oci_free_statement($cursor);


//get the number of users in DB so we can create the userid
$sql = "select total_users from countusers";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
$my_result = oci_fetch_array($cursor);
//creates and formats the next available userid #
//formatted value adds the leading 0's
$formatted_value = sprintf("%06d", $my_result[0]);
$userid = $finitial . $linitial . $formatted_value;

//creates the update statement for their student account

$sql = "update enduser  
		set student_flag = '1', age='$age', status='0', street='$street', 
		city='$city', st_state='$state', zip='$zip', st_type='U', admin_flag='0'
		where userid='$userid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];


if ($result == false){
  // Error handling interface.
  echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  <form method=\"post\" action=\"admin_add.php?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$userid\" name=\"userid\">
 
  <input type=\"hidden\" value = \"$password\" name=\"password\">
  <input type=\"hidden\" value = \"$usertype\" name=\"usertype\">
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

$sql = "create or replace view users as
		select userid, student_flag, admin_flag
		from enduser";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
oci_free_statement($cursor);
// Record inserted.  Go back.
Header("Location:admin.php?sessionid=$sessionid");

?>
