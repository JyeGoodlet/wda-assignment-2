<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/16/2015
 * Time: 10:36 PM
 */

require "/Model/ModelFacade.php";

OnRequest();

function OnRequest() {
    $threads = null;
    if (isset($_GET['search'])) {
        $threads = ModelFacade::SearchThreads($_GET["search"]);
        include_once('/Views/Search.html');
    }
    else {
        header( 'Location: Index.php' ) ;
    }





}

?>