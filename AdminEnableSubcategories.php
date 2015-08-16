<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest() {
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $isError = false;
        $categories = ModelFacade::getAllCategoriesWithSubcategories();

        foreach($categories as $category) {
            foreach($category->subcategories as $subcategory) {

                $isOffline = false;
                
                if(isset($_POST['boardState'])) {
                    foreach($_POST['boardState'] as $offlineId) {
                        if($subcategory->id === $offlineId) {
                            $isOffline = true;
                        }
                    }   
                }
                $errorCode = ModelFacade::UpdateSubcategoryState($subcategory->id, $isOffline);     
                
                if($errorCode[0] != 0) {
                    $isError = true;
                }
            }
        }

        if($isError) {
            $error = "There was an error updating the subcategory states";
        }
        else {
            $success = "Subcategory Online/Offline states successfully updated";
        }

        $categories = ModelFacade::getAllCategoriesWithSubcategories();         
        include_once('/Views/Admin/EnableSubcategory.html');        
    }
    else {
        
        $categories = ModelFacade::getAllCategoriesWithSubcategories();         
        include_once('/Views/Admin/EnableSubcategory.html');
    }

}

?>