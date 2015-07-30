<?php

require "User.php";

Class ModelFacade {


	public static function login($username, $password) {
		//
		$user = new User($username, $password);
		
		if ($user->attempLogin()) {
			$_SESSION["user"] = $user;
		}
		
		

	}

	public static function checkLoggedIn() {
		if (isset($_SESSION['user'])) {
			return true;
		}
		else {
			return false;
		}
	}


}




?>