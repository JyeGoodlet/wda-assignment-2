<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/2/2015
 * Time: 5:50 PM
 */

//Require Model
require "/Model/ModelFacade.php";
ModelFacade::logout();
ModelFacade::redirectUnauthorises();

?>
