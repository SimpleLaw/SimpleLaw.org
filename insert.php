<?php
	$lawtitle=$_POST['lawtitle'];
	$lawtrackingnumber=$_POST['lawtrackingnumber'];
	$category=$_POST['category'];
	$datesigned=$_POST['datesigned'];
	$tags=$_POST['tags'];
	if (file_exists("../Bills/" . $_FILES["file"]["name"])) {
		echo $_FILES["file"]["name"] . " already exists. ";
	} else {
		$correctedname=explode("\\", $_FILES["file"]["tmp_name"]);
		$correctedname=implode("\\",$correctedname);
		echo($correctedname);
		if(move_uploaded_file($correctedname,$_FILES["file"]["name"])){
			echo("it works");
		}
		else{
			echo("fuck you");
		}
		
	}

	//mysql_connect('50.62.209.5','Pgma14','Geogre333') or die ("<html><script language='JavaScript'>alert('Unable to connect to database! Please try again later.'),history.go(-1)</script></html>");
	//mysql_select_db('Pgma14');
//mysql_query("INSERT INTO Laws VALUES('$lawtitle','$lawtrackingnumber','$category','$datesigned','$tags','$billtext')");

	$username=$_POST["username"];
	$password = $_POST["pwd"];
	/*echo("<html>
		<script>
			function login(){var form = document.forms['login'];
				form.submit();}
			window.onload = login;
		</script>
		<form id='login' action='admin.php' method='post'>
			<input type='hidden' name='username' value='$username'>
			<input type='hidden' name='pwd' value='$password'>
		</form>
	</html>");*/

?>