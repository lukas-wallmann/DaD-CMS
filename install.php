<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if(isset($_GET["install"])){
		$con = mysqli_connect($_POST["dbhost"],$_POST["dbuser"],$_POST["dbpass"],$_POST["dbname"]);

		// Check connection
		if (mysqli_connect_errno()){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}else{

			$settings='<?php $_DB=array("'.$_POST["dbhost"].'","'.$_POST["dbuser"].'","'.$_POST["dbpass"].'","'.$_POST["dbname"].'"); ?>';
			$myfile = fopen("admin/library/settings.php", "w") or die("Unable to open file!");
			fwrite($myfile, $settings);
			fclose($myfile);
			header('Location: admin/');
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
<title>Installation of newCMS</title>
</head>

<body>
<h1>Welcome to install</h1>
<form method="post" action="?install=1">
	<div><label>Database host:</label><input name="dbhost" value="localhost"></div>
	<div><label>Database name:</label><input name="dbname" value="newcms"></div>
	<div><label>Database user:</label><input name="dbuser"></div>
	<div><label>Database password:</label><input name="dbpass" type="password"></div>
	<div><label>Use caching:</label><input type="checkbox" name="caching" checked></div>
	<button type="submit">install</button>
</form>
</body>

</html>
