<?php
require "/Model/ModelFacade.php";
/*
 * This controller is used to allow an admin to delete a users account.
 *
 */

//redirect if user not logged in as admin
ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest()
{

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        if (isset($_GET['id'])) {
            DeleteUser($_GET['id']);
        }
    }

}

function DeleteUser($id)
{

    $result = ModelFacade::DeleteUser($id);
    if ($result[0] == 0) {
        header('Location: /AdminUsers.php');
    } else {
        header('Location: /AdminUser.php?id=' . $id);
    }


}

function UpdateUser()
{

    $user = GetUserById($_GET['id']);

    if (isset($_POST['is_admin']) && isset($_POST['is_banned'])) {
        $userArray = array('id' => $user->id,
            'is_admin' => $_POST['is_admin'],
            'is_banned' => $_POST['is_banned']);

        $result = ModelFacade::UpdateUser($userArray);

        switch ($result[0]) {
            case 0:
                $success = 'User ' . $user->username . ' updated successfully';
                break;
            default:
                $error = 'There was an error updating the users database';
                break;
        }

        $user = GetUserById($_GET['id']);
        include_once('/Views/Admin/User.html');

    } else {
        header("Location: /AdminUsers.php");
    }

}

function GetUserById($id)
{
    $user = ModelFacade::GetUserById($id);
    return $user;
}

?>