<?php

require "User.php";

Class ModelFacade {


	public static function login($username, $password) {
		//
		$user = new User($username, $password);
		
		if ($user->attempLogin()) {
			session_start();
			$_SESSION["user"] = $user;

		}

		
		

	}

    public static function getLoggedInUser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION["user"] ;
    }

	public static function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		session_destroy();
	}

	public static function signup($username, $password) {
		$user = new User($username, $password);
		$user->signup();

	}

	public static function checkLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		if (isset($_SESSION['user'])) {
			return true;
		}
		else {
			return false;
		}
	}

	public static function redirectUnauthorises() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		if (!isset($_SESSION['user'])) {
			header("Location: /Login");
		}

	}

    public static function checkUsernameAvaiable($username) {
        $user = new User($username, "");
        $avaiable = $user->checkUsernameAvailable();
        return $avaiable;

    }





}




?>