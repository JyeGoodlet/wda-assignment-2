<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest()
{

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['selectCategoryName']) && isset($_POST['categoryName'])) {
            AdminEditBoard($_POST['selectCategoryName'], $_POST['categoryName']);
        }
    } else {

        $categories = ModelFacade::getAllCategoriesWithSubcategories();
        include_once('/Views/Admin/EditBoard.html');
    }

}

function AdminEditBoard($id, $categoryName)
{
    $id = htmlspecialchars($id);
    $categoryName = htmlspecialchars($categoryName);

    if ($id != -1) {
        if (!empty($categoryName)) {
            $result = ModelFacade::AdminEditBoard($id, $categoryName);

            if ($result) {
                switch ($result[0]) {
                    case 0:
                        $success = "Board " . $categoryName . " successfully updated!";
                        break;
                    default:
                        $error = "There was an error editing " . $categoryName . ": code = " . $result[0];
                        break;
                }
            } else {
                $error = $categoryName . " already exists!";
            }
        } else {
            $error = "Error - Category Name must not be empty!";
        }
    } else {
        $error = "Error - Category to edit must be selected!";
    }
    $categories = ModelFacade::getAllCategoriesWithSubcategories();
    include_once('/Views/Admin/EditBoard.html');
}

?>