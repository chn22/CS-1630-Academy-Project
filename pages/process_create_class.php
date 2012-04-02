<?
	require("../glue.php");
	init("form_process");
	
	
/* PLEASE CAREFULLY READ THESE COMMENTS!
 * 
 * On form_process pages, you will need to work with $_POST variables.  PLEASE SPECIFY WHAT THESE ARE NAMED AND WHAT YOU REQUIRE so we can make sure they are delivered by the form.  Do not worry about the token, that is taken care of automatically
 * 
 * use $_SESSION["username"] and $_SESSION["usertype"] to segregate the components of the page (i.e. if ($username != "admin") etc.)
 * use $db to make database calls
 *
 * CAREFULLY use the defines.php file (includes/definies.php) to define any important information like file paths - specifically anything that may chance from one person's machine to another or on the production server.  This makes sure we can just change things here and they won't break elsewhere.  Please name your defines carefully.
 *
 * Make sure to capture any errors or failure so the user doesn't get stuck on a blank page.
 * 
 */

	if($_SESSION["usertype"] != "teacher" && $_SESSION["usertype"] != "admin")
	{
		return_to(HOME_DIR);
	}
	
	$class_name = sqlite_escape_string(trim($_POST['class_name']));
	$instructor_email = sqlite_escape_string(trim($_POST['instructor_email']));
	$room = sqlite_escape_string(trim($_POST['room']));
	$description = sqlite_escape_string(trim($_POST['description']));
	
	$query = "select user_id from User where email = '$instructor_email'";
	$results = $db->arrayQuery($query);
	if(empty($results)){
		$_SESSION["creation-message-error"] = "Error inserting class into database: instructor email not found";
		return_to("view_classes.php");
	}
	else{
		$instructor_id = $results[0]['user_id'];
		$query = "insert into Class values(NULL, '$class_name', '$instructor_id', '$instructor_email', '$room', '$description')";
		$result = $db->queryExec($query, $error);
		if (empty($result))
		{
			$_SESSION["creation-message-error"] = "Error inserting class into database: $error";
		}
		else
		{
			$_SESSION["creation-message"] = "Class successfully created.";
		}
			
		return_to("view_classes.php");
	}
?>