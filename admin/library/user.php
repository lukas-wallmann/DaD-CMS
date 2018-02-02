<?php
	session_start();

	function login($user,$pass){
		global $_dbcon,$_maindir;
		$user=mysqli_real_escape_string($_dbcon,$user);
		$pass=mysqli_real_escape_string($_dbcon,$pass);
		$res=mysqli_query($_dbcon,"Select * From users Where User='$user'");
		while($row=mysqli_fetch_assoc($res)){
			if(password_verify($pass,$row["Password"])){
				setLoginSession($row["ID"],$user,$row["Password"],$row["Language"],$row["Rights"]);
				header("Location:".$_maindir."admin/?loggedin=1");
			}else{
				header("Location:".$_maindir."admin/login.php");
			}
		}
	}

	function setLoginSession($id,$user,$pass,$lang,$rights){
		$_SESSION["id"]=$id;
		$_SESSION["user"]=$user;
		$_SESSION["pass"]=$pass;
		$_SESSION["lang"]=$lang;
		$_SESSION["rights"]=$rights;
	}

	function checkLogin(){
		global $_dbcon,$_maindir;
		if(isset($_SESSION["id"])){
			$id=$_SESSION["id"];
			$pass=$_SESSION["pass"];
			$res=mysqli_query($_dbcon,"Select * FROM users Where ID=$id AND Password='$pass'");
			if(mysqli_num_rows($res)!=1){
				header("Location:".$_maindir."admin/login.php");
			}else{
				setLoginSession($_SESSION["id"],$_SESSION["user"],$_SESSION["pass"],$_SESSION["lang"],$_SESSION["rights"]);
			}
		}else{
			header("Location:".$_maindir."admin/login.php");
		}
	}
	if(!isset($_noLogin))checkLogin();
	if(isset($_GET["login"])){
		login($_POST["user"],$_POST["pass"]);
	}
