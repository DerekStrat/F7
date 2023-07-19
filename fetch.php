<?php
include('person_class.php');
include('item_generator.php'); // php file containing random DB entry info

if (isset($_GET["type"])) {
    $NewPersonObj = new Person();
    $ResultObj = $NewPersonObj->loadAllPeople();
    // error_log(json_encode($ResultObj));
    echo json_encode($ResultObj);
}

if (isset($_POST["func"])) {
    $NewPersonObj = new Person();
    switch($_POST["func"]) {
        case "generateAllPeople":
            $InitRunFlt = microtime(true);
            $ResultObj = $NewPersonObj->generatePeople($ItemsArr);
            $EndRunFlt = microtime(true);
            $ExecutionTimeFlt = ($EndRunFlt - $InitRunFlt);
            echo json_encode($ExecutionTimeFlt);
        break;
        case "deleteAllPeople":
            $NewPersonObj->deleteAllPeople();
            $ResultObj = $NewPersonObj->loadAllPeople();
            echo json_encode($ResultObj);
        break;
        case "submitPerson":
            $ResultObj = $NewPersonObj->createPerson($_POST["FirstName"], $_POST["LastName"], $_POST["DateOfBirth"], $_POST["EmailAddress"]); 
            echo json_encode($ResultObj);
        break;
        case "deletePerson":
            $NewPersonObj->deletePerson($_POST["id"]);
        break;
        case "getPerson":
            $ResultObj = $NewPersonObj->getPerson($_POST["id"]);
            echo json_encode($ResultObj);
        break;
        case "updatePerson":
            $ResultObj = $NewPersonObj->loadPerson($_POST["id"], $_POST["FirstName"], $_POST["LastName"], $_POST["DateOfBirth"], $_POST["EmailAddress"]);
            echo json_encode($ResultObj);
        break;
    }
}
  
?>