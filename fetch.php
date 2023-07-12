<?php
include('person_class.php');
include('item_generator.php'); // php file containing random DB entry info

if (isset($_GET["type"])) {
    $NewPersonObj = new Person();
    $ResultObj = $NewPersonObj->loadAllPeople();
    echo json_encode($ResultObj);
}

if (isset($_POST["func"])) {
    switch($_POST["func"]) {
        case "generateAllPeople":
            $InitRunFlt = microtime(true);
            $NewPersonObj = new Person();
            $ResultObj = $NewPersonObj->generatePeople($ItemsArr);
            $EndRunFlt = microtime(true);
            $ExecutionTimeFlt = ($EndRunFlt - $InitRunFlt);
            echo json_encode($ExecutionTimeFlt);
        break;
        case "deleteAllPeople":
            $NewPersonObj = new Person();
            $NewPersonObj->deleteAllPeople();
            $ResultObj = $NewPersonObj->loadAllPeople();
            echo json_encode($ResultObj);
        break;
        case "submitPerson":
            $NewPersonObj = new Person();
            $NewPersonObj->createPerson("alan", "Tate", '1999-06-02', "example@gmail.com", 5);
            $ResultObj = $NewPersonObj->savePerson();
            echo json_encode($ResultObj);
        break;
        case "deletePerson":
            $NewPersonObj = new Person();
            $NewPersonObj->deletePerson("emailAddress");
        break;
    }
}

?>