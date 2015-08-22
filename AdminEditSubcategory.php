<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();

OnRequest();

function OnRequest()
{

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['selectSubcategoryName']) && isset($_POST['subcategoryName'])) {
            AdminEditSubcategory($_POST['selectSubcategoryName'], $_POST['subcategoryName']);
        }
    } else {

        $categories = ModelFacade::getAllCategoriesWithSubcategories();
        include_once('/Views/Admin/EditSubcategory.html');
    }

}

function AdminEditSubcategory($id, $subCategoryName)
{
    $id = htmlspecialchars($id);
    $subCategoryName = htmlspecialchars($subCategoryName);

    if ($id != -1) {
        if (!empty($subCategoryName)) {
            $result = ModelFacade::AdminEditSubcategory($id, $subCategoryName);

            if ($result) {
                switch ($result[0]) {
                    case 0:
                        $success = "Board " . $subCategoryName . " successfully updated!";
                        break;
                    default:
                        $error = "There was an error editing " . $subCategoryName . ": code = " . $result[0];
                        break;
                }
            } else {
                $error = $subCategoryName . " already exists!";
            }
        } else {
            $error = "Error - Subcategory Name must not be empty!";
        }
    } else {
        $error = "Error - Subcategory to edit must be selected!";
    }
    $categories = ModelFacade::getAllCategoriesWithSubcategories();
    include_once('/Views/Admin/EditSubcategory.html');
}

?>