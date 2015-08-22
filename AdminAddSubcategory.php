<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest()
{

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['selectCategoryName']) && isset($_POST['subcategoryName'])) {
            AdminAddSubcategory($_POST['selectCategoryName'], $_POST['subcategoryName']);
        }
    } else {
        $categories = ModelFacade::getAllCategoriesWithSubcategories();
        include_once('/Views/Admin/AddSubcategory.html');
    }

}

function AdminAddSubcategory($categoryId, $subcategoryName)
{
    $categoryId = htmlspecialchars($categoryId);
    $subcategoryName = htmlspecialchars($subcategoryName);
    $result = ModelFacade::AdminAddSubcategory($categoryId, $subcategoryName);
    $category = ModelFacade::getCategory($categoryId);

    if ($categoryId != -1) {
        if (!empty($subcategoryName)) {
            if ($result) {
                switch ($result[0]) {
                    case 0:
                        $success = "Subcategory '" . $subcategoryName . "' successfully added to '" . $category['category'] . "'!";
                        break;
                    default:
                        $error = "There was an error adding '" . $subcategoryName . "' to '" . $category['category'] . "': code = " . $result[0];
                        break;
                }
            } else {
                $error = "'" . $subcategoryName . "' already exists in Category '" . $category['category'] . "'!";
            }
        } else {
            $error = "Error - Subcategory Name must not be empty!";
        }
    } else {
        $error = "Error - Parent Category must be selected!";
    }

    $categories = ModelFacade::getAllCategoriesWithSubcategories();
    include_once('/Views/Admin/AddSubcategory.html');
}

?>