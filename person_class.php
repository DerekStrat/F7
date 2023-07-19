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
    public string $FirstNameStr;
    public string $LastNameStr;
    public string $DateOfBirthStr;
    public string $EmailAddressStr;

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
        $BirthDayObj = new DateTime($DateOfBirthStr);
        $TodayObj = new DateTime(date('y-m-d'));
        $DiffObj = $TodayObj->diff($BirthDayObj);

        return $DiffObj->y;
    }

    function getPerson($RowIdStr) {
        $RowIdInt = number_format($RowIdStr);

        $this->createConn();

        $SelectRowSqlStr = "SELECT * FROM person WHERE ID = $RowIdInt";
        $ResultObj = $this->ConnectionObj->query($SelectRowSqlStr);
        $RowArr = $ResultObj->fetch_assoc();

        $this->endConn();

        return $RowArr;
    }


    function savePerson() { // checking the information
        $this->EmailAddressStr = strtolower($this->EmailAddressStr);

        $TodayObj = new DateTime(date('y-m-d'));
        $SelectedDateObj = new DateTime($this->DateOfBirthStr);
        
        if (!preg_match("/^[a-zA-Z-' ]*$/", $this->FirstNameStr)) {
            return "Only letters and whitespaces can be used for the name-field.";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $this->LastNameStr)) {
            return "Only letters and whitespace can be used for the surname-field.";
        } elseif (!filter_var($this->EmailAddressStr, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        } elseif ($TodayObj < $SelectedDateObj) {
            return "The latest date you can choose is today.";
        }  else {
            $this->FirstNameStr = strtolower($this->FirstNameStr);
            $this->FirstNameStr = ucwords($this->FirstNameStr);
    
            $this->LastNameStr = strtolower($this->LastNameStr);
            $this->LastNameStr = ucwords($this->LastNameStr);
    
            return "none";
        }
    }


    function createPerson($FirstNameStr, $LastNameStr, $DateOfBirthStr, $EmailAddressStr) { 
        $this->FirstNameStr = $FirstNameStr;
        $this->LastNameStr = $LastNameStr;
        $this->DateOfBirthStr = $DateOfBirthStr;
        $this->EmailAddressStr = $EmailAddressStr;
        $ResultStr = $this->savePerson();

        if ($ResultStr != "none") {
            return $ResultStr;
        } else {
            $AgeInt = $this->calcAge($DateOfBirthStr);

            $this->createConn();

            // insert
            $InsertToSQL = "INSERT INTO person (FirstName, LastName, DateOfBirth, EmailAddress, Age)
                    VALUES ('".$this->FirstNameStr."', '".$this->LastNameStr."', '".$this->DateOfBirthStr."', '".$this->EmailAddressStr."', $AgeInt)";
            $this->ConnectionObj->query($InsertToSQL);

            $this->endConn();
            return 1;
        }
    }


    function loadPerson($RowIdStr, $FirstNameStr, $LastNameStr, $DateOfBirthStr, $EmailAddressStr) { // update a person object in the database (editing)
        $this->FirstNameStr = $FirstNameStr;
        $this->LastNameStr = $LastNameStr;
        $this->DateOfBirthStr = $DateOfBirthStr;
        $this->EmailAddressStr = $EmailAddressStr;
        $ResultStr = $this->savePerson();

        if ($ResultStr != "none") {
            return $ResultStr;
        } else {
            $AgeInt = $this->calcAge($DateOfBirthStr);
            $RowIdInt = number_format($RowIdStr);

            $this->createConn();
            
            // Update item
            $UpdateEntrySql = "UPDATE person
            SET FirstName = '".$this->FirstNameStr."', 
            LastName = '".$this->LastNameStr."',
            DateOfBirth = '".$DateOfBirthStr."',
            EmailAddress = '".$this->EmailAddressStr."',
            Age = '$AgeInt'
            WHERE ID = $RowIdInt" ;
            $this->ConnectionObj->query($UpdateEntrySql);

            $this->endConn();

            return 1;
        }
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
            $AgeInt  = $this->calcAge($ItemsArr[2][$counter]);

            $InsertToSqlStr = "INSERT INTO person (FirstName, LastName, DateOfBirth, EmailAddress, Age)
                VALUES ('".$NameStr."', '".$SurNameStr."', '".$DateStr."', '".$EmailStr."', $AgeInt)";
            $ResultObj = $this->ConnectionObj->query($InsertToSqlStr);
            if (!$ResultObj) {
                die("Invalid query: " . $this->ConnectionObj->error);
            }
        }

        $this->endConn();
    }


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