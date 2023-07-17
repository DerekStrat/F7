<?php
/*
Create a class in your php script called "Person" that will have 6 functions:
- createPerson
- loadPerson
- savePerson
- deletePerson
- loadAllPeople
- deleteAllPeople 
*/

class Person {
    private $ConnectionObj;

    /**
     * Checks if query to DB was success/error
     */
    function checkQuery($Query) { // checking Query succesful
        if ($this->ConnectionObj->query($Query) === TRUE) {
            echo "Success<br>";
            } else {
            echo "Error: " . "<br>" . $this->ConnectionObj->error;
            }
    }

    /**
     * Create connection to DB 'foundation_task_6'
     */
    function createConn() {
        $this->ConnectionObj = new mysqli("localhost", "root", "", "foundation_task_6");
        if ($this->ConnectionObj->connect_error) {
            die("Connection failed: " . $this->ConnectionObj->connect_error);
        }
    }

    /**
     * End the connection to DB 'foundation_task_6'
     */
    function endConn() {
        $this->ConnectionObj->close();
    }


    function calcAge($DateOfBirthStr) { // calculating the age of a user when date of birth is given
        // $NewDateOfBirthStr = strval($DateOfBirthStr);
        // error_log($NewDateOfBirthStr);
        $BirthDayObj = new DateTime($DateOfBirthStr); // error
        $TodayObj = new DateTime(date('y-m-d'));
        $DiffObj = $TodayObj->diff($BirthDayObj);

        return $DiffObj->y;
    }


    function savePerson($FirstNameStr, $LastNameStr, $DateOfBirthStr, $EmailAddressStr) { // Adding a person to the DB (New Client)
        $AgeInt = $this->calcAge($DateOfBirthStr);
        
        $this->createConn();

        // insert
        $InsertToSQL = "INSERT INTO person (FirstName, LastName, DateOfBirth, EmailAddress, Age)
                VALUES ('".$FirstNameStr."', '".$LastNameStr."', '".$DateOfBirthStr."', '".$EmailAddressStr."', $AgeInt)";
        $this->ConnectionObj->query($InsertToSQL);

        $this->endConn();
    }


    function createPerson($FirstNameStr, $LastNameStr, $DateOfBirthStr, $EmailAddressStr) { 
        $EmailAddressStr = strtolower($EmailAddressStr);
        
        if (!preg_match("/^[a-zA-Z-' ]*$/", $FirstNameStr)) {
            return "Only letters and whitespaces can be used for the name-field.";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $LastNameStr)) {
            return "Only letters and whitespace can be used for the surname-field.";
        } elseif (!filter_var($EmailAddressStr, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        } else {
            $FirstNameStr = strtolower($FirstNameStr);
            $FirstNameStr = ucwords($FirstNameStr);
    
            $LastNameStr = strtolower($LastNameStr);
            $LastNameStr = ucwords($LastNameStr);
    
            $this->savePerson($FirstNameStr, $LastNameStr, $DateOfBirthStr, $EmailAddressStr);
            return 1;
        }
    }


    function loadPerson($InputID) { // update a person object in the database (editing)
        // How to get Row ID from js
        $this->createConn();
        
        // Update item
        $UpdateEntrySql = "UPDATE person
        SET FirstName = '$this->FirstNameStr',
        LastName = '$this->SurNameStr',
        DateOfBirth = '$this->DateOfBirthStr',
        EmailAddress = '$this->EmailAddressStr',
        Age = '$this->AgeInt'
        WHERE ID = $InputID" ;
        $this->ConnectionObj->query($UpdateEntrySql);
        
        $this->endConn();
    }


    function deletePerson($RowIdStr) {
        $RowIdInt = number_format($RowIdStr);

        $this->createConn();

        // Delete
        $DeleteOneRow = "DELETE FROM person WHERE ID = $RowIdInt";
        $this->ConnectionObj->query($DeleteOneRow);

        $this->endConn();
    }


    function generatePeople($ItemsArr) { // Generate 10 people on screen & load in DB
        $this->createConn();

        for ($counter = 0; $counter < 10; $counter++) {
            $NameStr  = $ItemsArr[0][$counter];
            $SurNameStr  = $ItemsArr[1][$counter];
            $DateStr  = $ItemsArr[2][$counter];
            $EmailStr  = $ItemsArr[3][$counter];
            $AgeInt  = $ItemsArr[4][$counter];

            $InsertToSqlStr = "INSERT INTO person (FirstName, LastName, DateOfBirth, EmailAddress, Age)
                VALUES ('".$NameStr."', '".$SurNameStr."', '".$DateStr."', '".$EmailStr."', $AgeInt)";
            $ResultObj = $this->ConnectionObj->query($InsertToSqlStr);
            if (!$ResultObj) {
                die("Invalid query: " . $this->ConnectionObj->error);
            }
        }

        $this->endConn();
    }


    /**
     * Returns all entries of 'person' table
     * @return array
     */
    function loadAllPeople() { // load all objects to F6.js
        $this->createConn();

        // read all items from database table
        $SelectAllPeopleSqlStr = "SELECT * FROM person";
        $ResultObj = $this->ConnectionObj->query($SelectAllPeopleSqlStr);
        if (!$ResultObj) {
            die("Invalid query: " . $this->ConnectionObj->error);
        }

        // create an array for js file
        $ObjectArr = array();

        // Read data of each row
        while($RowArr = $ResultObj->fetch_assoc()) {
            array_push($ObjectArr, $RowArr);
        }

        // end connection
        $this->endConn();
        
        // return array
        return $ObjectArr;
    }

    /**
     * Delete *ALL* entries in the database 'person' table
     */
    function deleteAllPeople() {
        $this->createConn();

        // Delete everything
        $DeleteAllElements = "TRUNCATE TABLE person";
        $this->ConnectionObj->query($DeleteAllElements);

        $this->endConn();
    }
}

?>