<?
include "utility_functions.php";

// Get the client id and password and verify them
$userid = $_POST["userid"]; //admin
$password = $_POST["password"]; //league

$sql = "select userid " .
       "from enduser " .
       "where userid='$userid'and password='$password'";

//passes the sql command to find the userid and password and sees if they exist
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if($values = oci_fetch_array ($cursor)){
  oci_free_statement($cursor);

  // found the client
  $userid = $values[0];
  
  // create a new session for this client
  $sessionid = md5(uniqid(rand()));

  // store the link between the sessionid and the clientid
  // and when the session started in the session table

  $sql = "insert into usersession " .
    "(sessionid, userid, sessiondate) " .
    "values ('$sessionid', '$userid', sysdate)";

  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($result == false){
    display_oracle_error_message($cursor);
    die("Failed to create a new session");
  }
  else {
    // get the student flag
    $susertype = "select student_flag " .
       "from enduser " .
       "where userid='$userid'and password='$password'";

    $result_array = execute_sql_in_oracle ($susertype);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];
    $stype = oci_fetch_array ($cursor);
	//get the admin flag
	$ausertype = "select admin_flag " .
       "from enduser " .
       "where userid='$userid'and password='$password'";

    $result_array = execute_sql_in_oracle ($ausertype);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];
    $atype = oci_fetch_array ($cursor);

    if ($stype[0] == 1 AND $atype[0] == 1){
      header("Location:welcomepage.php?sessionid=$sessionid");
    }
    else if ($atype[0] == 1){
      header("Location:admin.php?sessionid=$sessionid");
    }
	else{
		header("Location:student.php?sessionid=$sessionid");
	}
  }
}
else { 
  // client username not found
  die ('Login failed.  Click <A href="index.html">here</A> to go back to the login page.');
} 
?>