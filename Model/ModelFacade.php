<?php

require "User.php";

Class ModelFacade {


	public static function login($username, $password) {
		//
		$user = new User($username, $password);
		$user->attempLogin();
		echo "login attempted ";

	}


}




?>