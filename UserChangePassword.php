<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if ($requestMethod == "GET") {

        if (isset($_GET['id'])) {
            $currentUser = ModelFacade::getLoggedInUser();
            $userDetails = ModelFacade::getUserDetails($_GET['id']);

            if ($userDetails) {
                if ($userDetails->id != $currentUser->id) {
                    $message = "Access denied.";
                    include_once('/Views/ErrorPage.html');
                } else {
                    include_once('/Views/UserChangePassword.html');
                }
            } else {
                $message = "No user exists with the specified id";
                include_once('/Views/ErrorPage.html');
            }

        } else {
            $message = "Sorry no user id was set";
            include_once('/Views/ErrorPage.html');
        }
    } else {
        ChangePassword();
    }

}

function ChangePassword()
{

    if (isset($_GET['id'])) {
        $currentUser = ModelFacade::getLoggedInUser();
        $userDetails = ModelFacade::getUserDetails($_GET['id']);
    }

    $oldPassword = htmlspecialchars($_POST['oldPassword']);
    $newPassword = htmlspecialchars($_POST['newPassword']);
    $confirmPassword = htmlspecialchars($_POST['confirmPassword']);

    //Confirm old password is correct:
    if (ModelFacade::confirmPassword($_GET['id'], $oldPassword)) {

        if (strlen($newPassword) < 6)
            $error = "Password must be more then 6 characters";
        else if ($newPassword == "" || $newPassword == null)
            $error = "password must not be empty";
        else if ($newPassword != $confirmPassword) {
            $error = "passwords do not match";
        } else {
            $errorCode = ModelFacade::updatePassword($_GET['id'], $newPassword);
            if ($errorCode[0] == 0) {
                $success = "Password successfully updated!";
            } else {
                $error = "There was an error updating your password: Code " . $errorCode[0];
            }
        }

        include_once('/Views/UserChangePassword.html');
    } else {
        $error = "The password you entered was incorrect";
        include_once('/Views/UserChangePassword.html');
    }
}