<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest()
{

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['categoryName'])) {
            AdminAddBoard($_POST['categoryName']);
        }
    } else {
        include_once('/Views/Admin/AddBoard.html');
    }

}

function AdminAddBoard($categoryName)
{
    $categoryName = htmlspecialchars($categoryName);
    $result = ModelFacade::AdminAddBoard($categoryName);

    if ($result) {
        switch ($result[0]) {
            case 0:
                $success = "Board " . $categoryName . " successfully added!";
                break;
            default:
                $error = "There was an error adding " . $categoryName . ": code = " . $result[0];
                break;
        }
    } else {
        $error = $categoryName . " already exists!";
    }

    include_once('/Views/Admin/AddBoard.html');
}

?>