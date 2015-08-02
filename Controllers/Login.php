<?php

//Require Model
require "../Model/ModelFacade.php";

OnRequest();

function OnRequest() {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if (ModelFacade::checkLoggedIn()) {
        //redirect if user is logged in
        header("Location: /Index");

    }
    if ($requestMethod == "GET") {
        loginGet();
    }
    else {
        loginPost();
    }
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
            header( 'Location: index' ) ;
            exit();
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