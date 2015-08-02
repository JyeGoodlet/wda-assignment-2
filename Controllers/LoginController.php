<?php
//Require Model
require "../Model/ModelFacade.php";

OnRequest();

function OnRequest() {
	$requestMethod = $_SERVER['REQUEST_METHOD'];
	if (!isset($_GET['page']))
	{
		
		if ($requestMethod == "GET") {
			loginGet();	
		}
		else {
			loginPost();
		}
	}
	else if (isset($_GET['page'])) {
		$page = $_GET['page'];
		if ($page == "Signup") {

			SignUpGet();
			if ($requestMethod == "GET") {
				SignupGet();
			}
			else {
				//loginPost("", "");
				SignupPost();
			}
		}

		else {
			echo "404 error page";
		}
	}
	else {
		echo "ERROR PAGE";
	}
	
}

function SignupGet() {
	include_once("../Views/Signup.html");

}

function SignupPost() {
	ModelFacade::signup($_POST["username"], $_POST["password"]);
	include_once("../Views/Signup.html");
}

function loginGet() {
	//echo "Get";
	include_once("../Views/login.html");


}


function loginPost() {
	if (isset($_POST["username"]) && isset($_POST["password"])) {

		//Attemp to log user in
		ModelFacade::login($_POST["username"], $_POST["password"]);
		if (ModelFacade::checkLoggedIn()) {
			//redirect
			header( 'Location: /index' ) ;
		}
		else {
			$message = "Username or password does not exist";
			include_once("../Views/login.html");
		}
		
		
	}
	else {
		$message = "Please enter username and password";
		include_once("../Views/login.html");
	}

	

}




?>