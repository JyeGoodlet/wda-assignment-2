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
    if (isset($_GET['delAccount']))
        $message = "Your Account has been successfully deleted";
    include_once("/Views/Signup.html");

}

function SignupPost() {

    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $passwordConfirm = htmlspecialchars($_POST['passwordconfirm']);

    //username validation
    if (!ModelFacade::checkUsernameAvaiable($username))
        $message = "Username: " .  $username .  " is not available";
    else if (strlen($username) < 6)
        $message = "username must be 6 or more characters";
    else if (!preg_match("/^[a-zA-Z0-9]*$/",$username))
        $message = "username must be alphanumeric";

        //email validation
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $message = $email." is not a valid email address";
    else if ($_POST["email"] == "" || $_POST["email"] == null)
        $message = "email must not be empty";
    else if (!ModelFacade::checkEmailAvaiable($email))
        $message = "Email: " .  $email .  " has already been used to create an account.";

        //password validation
    else if (strlen($password ) < 6)
        $message = "password must be more then 6 characters";
    else if ($password == "" || $password == null)
        $message = "password must not be empty";
    else if ($password != $passwordConfirm) {
        $message = "passwords do not match";
    }
    else {

        //signup user
        ModelFacade::signup($_POST["username"], $_POST["password"], $_POST['email']);

        //log user in
        ModelFacade::login($_POST["username"], $_POST["password"] );

        //store that this is a new signup so user gets nice notification
        $_SESSION['newsignup'] = true;

        //redirect to index
        header( 'Location: Index.php' ) ;
        exit();


    }




    //
    include_once("/Views/Signup.html");
}


?>