 <?php
//Require Model
require "/Model/ModelFacade.php";

OnRequest();

function OnRequest() {
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if ($requestMethod == "GET") {
        SignupGet();
    }
    else {
        //loginPost("", "");
        SignupPost();
    }
}


function SignupGet() {
    include_once("../Views/Signup.html");

}

function SignupPost() {
    $username = $_POST["username"];
    if (!ModelFacade::checkUsernameAvaiable($username)) {
        $message = "Username: " .  $_POST["username"] .  " is not available";
    }
    else if (strlen($username) < 6) {
        $message = "username must be 6 or more characters";
    }
    else if (strlen($_POST["password"] ) < 6) {
        $message = "password must be more then 6 characters";
    }


    else if ($_POST["password"] == "" || $_POST["password"] == null) {

        $message = "password must not be empty";

    }
    else if ($_POST["password"] != $_POST["passwordconfirm"]) {
        $message = "passwords do not match";
    }
    else {
        ModelFacade::signup($_POST["username"], $_POST["password"]);
    }




    //
    include_once("../Views/Signup.html");
}


?>