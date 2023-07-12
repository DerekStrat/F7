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
    public string $SurNameStr;
    public string $DateOfBirthStr;
    public string $EmailAddressStr;
    public int $AgeInt;

    private $ConnectionObj;

    /**
     * Attributes of person
     * @param {str, str, str, str, int}
     */
    function createPerson($FirstNameStr, $SurNameStr, $DateOfBirthStr, $EmailAddressStr, $AgeInt) { 
        $this->FirstNameStr = $FirstNameStr;
        $this->SurNameStr = $SurNameStr;
        $this->DateOfBirthStr = $DateOfBirthStr;
        $this->EmailAddressStr = $EmailAddressStr;
        $this->AgeInt = $AgeInt;
    }

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


    function calcAge($Id) { // calculating the age of a user when date of birth is given
        $this->createConn();

        $Query = "SELECT * FROM person WHERE ID=$Id"; // selecting entry by ID
        $ResultObj = $this->ConnectionObj->query($Query); // query to DB
        $RowObj = $ResultObj->fetch_assoc(); // fetching associative array of entry
        $Bday = $RowObj["DateOfBirth"]; // Getting the specific type of element of entry

        $this->endConn();

        $BirthDay = new DateTime($Bday);
        $today = new DateTime(date('y-m-d'));
        $diff = $today->diff($BirthDay);
        // echo $today . "<br>";
        // var_dump($diff->y);
        // echo "<br><br>";
        // printf('your age : %d years', $diff->y);
        // printf("\n");
        return $diff->y;
    }


    function savePerson() { // Adding a person to the DB (add_person.html)
        $this->createConn();

        // insert
        $InsertToSQL = "INSERT INTO person (FirstName, LastName, DateOfBirth, EmailAddress, Age)
                VALUES ('$this->FirstNameStr', '$this->SurNameStr', '$this->DateOfBirthStr', '$this->EmailAddressStr', '$this->AgeInt')";
        $this->ConnectionObj->query($InsertToSQL);

        $this->endConn();
    }


    function loadPerson($InputID) { // update a person object in the database (editing)
        $this->createConn();
        
        // Update item
        $Query = "UPDATE person
        SET FirstName = '$this->FirstNameStr',
        LastName = '$this->SurNameStr',
        DateOfBirth = '$this->DateOfBirthStr',
        EmailAddress = '$this->EmailAddressStr',
        Age = '$this->AgeInt'
        WHERE ID = $InputID" ;
        $this->ConnectionObj->query($Query);

        $this->endConn();
    }


    function deletePerson($RowIdStr) { //delete an entry of the database (home_page.html)
        $this->createConn();

        // Delete
        $DeleteOneRow = "DELETE FROM person WHERE ID = '".$RowIdStr."'";
        $this->ConnectionObj->query($DeleteOneRow);

        $this->endConn();
    }


    function generatePeople($ItemsArr) { //Generate 10 people on screen & load in DB
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
            $PersonObj = new Person();
            $PersonObj->createPerson($RowArr["FirstName"], $RowArr["LastName"], $RowArr["DateOfBirth"], $RowArr["EmailAddress"], $RowArr["Age"]);

            array_push($ObjectArr, $PersonObj);
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