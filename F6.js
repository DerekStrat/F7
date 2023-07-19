// function hideModal() {
//     var x = document.getElementById("exampleModal");
//     if (x.style.display === "none") {
//       x.style.display = "block";
//     } else {
//       x.style.display = "none";
//     }
// }

function removeSetValue(id, att) {
    element = document.getElementById(id);
    element.removeAttribute("value");
    element.setAttribute("value", att);
}


function newClient() {
    // document.getElementById("recipient-form").reset();
    removeSetValue("recipient-name", "");
    removeSetValue("recipient-surname", "");
    removeSetValue("dateOfBirth", "");
    removeSetValue("recipient-email", "");

    document.getElementById("exampleModalLabel").firstChild.data = "New Client";
    document.getElementById("recipient-submit").firstChild.data = "Add Client";
    document.getElementById("recipient-submit").removeAttribute("onclick");
    document.getElementById("recipient-submit").setAttribute("onclick", "submitPerson()");
    // document.getElementById("recipient-close").firstChild.data = "Close";
    document.getElementById("recipient-submit").removeAttribute("disabled");
}


/**
 * remove ALL red borders of form input-box
 */
function removeRedBorderId() {
    idArray = ["recipient-name", "recipient-surname", "recipient-email", "dateOfBirth"];

    for (let x of idArray) {
        element = document.getElementById(x);
        element.removeAttribute("class");
        element.setAttribute("class", "form-control");
    }
}


/**
 * Add red border to form input-box 
 * @param {string} elem 
 */
function redBorderId(elem) {
    element = document.getElementById(elem);
    element.removeAttribute("class");
    element.setAttribute("class", "form-control border-danger");
}


function errField(errText) {
    Swal.fire({
        title:'Error!',
        text:errText,
        icon:'error',
        showConfirmButton: false,
        timer:3000
    })

    if (errText == "Only letters and whitespaces can be used for the name-field.") {
        redBorderId("recipient-name");
    } else if (errText == "Only letters and whitespace can be used for the surname-field.") {
        redBorderId("recipient-surname");
    } else if (errText == "Invalid email address.") {
        redBorderId("recipient-email");
    } else if (errText == "The latest date you can choose is today.") {
        redBorderId("dateOfBirth");
    } else {
        console.log("nothing found");
    }
}


function fillAllFields() {
    Swal.fire({
        title:'Important',
        text:'Please fill in all fields!',
        icon:'warning',
        showConfirmButton: false,
        timer:3000
    })
}


/**
 * deletePerson() sweet alert
 * @param {string} personId 
 * @param {string} personName 
 */
function delSweetAlert(personId, personName) {
    text = "Are you sure you want to delete ";
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      
      swalWithBootstrapButtons.fire({
        title: text.concat(personName, "?"),
        text: "You won't be able to reverse this.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: false
      }).then((result) => {
        if (result.isConfirmed) {
            deletePerson(personId);
            swalWithBootstrapButtons.fire({
                title:'Deleted!',
                text:personName.concat(" deleted successfully."),
                icon:'success',
                showConfirmButton: false,
                timer:1500
        });
        }
      })
}


/**
 * deleteAll() sweet alert
 */
function deleteAllSweetAlert() {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      
      swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to reverse this.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete all',
        cancelButtonText: 'Cancel',
        reverseButtons: false
      }).then((result) => {
        if (result.isConfirmed) {
            deleteAll();
            swalWithBootstrapButtons.fire({
                title:'Deleted!',
                text:'All entries of the table has been deleted.',
                icon:'success',
                showConfirmButton: false,
                timer:1500
        });
        } 
      })
}


function saveSweetAlert() {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Saved Successfully',
        showConfirmButton: false,
        timer: 2000
      })
}


/**
 * generateAllPeople() sweet alert
 */
function genSweetAlert() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      
      Toast.fire({
        icon: 'success',
        title: '10 People generated successfully'
      })
}


function submitPerson() {
    removeRedBorderId();
    if (document.forms["form1"]["fname"].value == "" || document.forms["form1"]["lname"].value == "" || document.forms["form1"]["formDate"].value == "" || document.forms["form1"]["email"].value == "") {
        fillAllFields();
    } else {
        firstName = document.getElementById("recipient-name").value; 
        lastName = document.getElementById("recipient-surname").value; 
        dateOfBirth = document.getElementById("dateOfBirth").value; 
        emailAddress = document.getElementById("recipient-email").value; 

        const postParameters =  new URLSearchParams();
        postParameters.append("func", "submitPerson");
        postParameters.append("FirstName", firstName);
        postParameters.append("LastName", lastName);
        postParameters.append("DateOfBirth", dateOfBirth); 
        postParameters.append("EmailAddress", emailAddress);
        fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: postParameters.toString()
            })
            .then(response => response.json())
            .then(data => {
                checkResponse(data);
            })
            .catch(err => console.error(err));
    }
}


function deletePerson(personId) {
    const postParameters =  new URLSearchParams();
    postParameters.append("func", "deletePerson");
    postParameters.append("id", personId);
    fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: postParameters.toString()
        })
        .then(loadAllPeople())
        .catch(err => console.error(err)); // error?
}


function deleteAll() {
    const postParameters =  new URLSearchParams();
    postParameters.append("func", "deleteAllPeople");
    fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: postParameters.toString()
        })
        .then(response => {
            const data = response.json();
            console.log(response);
        })
        .then(data => {loadAllPeople()})
        .catch(err => console.error(err));
}


function generateAllPeople() {
    const postParameters =  new URLSearchParams();
    postParameters .set("func", "generateAllPeople");
    fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: postParameters.toString()
        })
        .then(response => response.json())
        .then(data => {
            loadAllPeople();
            console.log("execution time: " + data);
            genSweetAlert();
        })
        .catch(err => console.error(err));
}


function loadAllPeople() {
    fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php?type=all')
        .then(response => response.json())
        .then(data => document.getElementById("person").replaceWith(createTableFromObjects(data)))
        .catch(err => console.error(err));
}

function checkResponse(data) {
    if (data == 1) {
        document.getElementById("recipient-submit").setAttribute("disabled","");
        saveSweetAlert();
        document.getElementById("recipient-form").reset();
        loadAllPeople();
    } else {
        errField(data);
    }
}


function getPerson(personId) {
    const postParameters =  new URLSearchParams();
    postParameters.append("func", "getPerson");
    postParameters.append("id", personId);
    fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: postParameters.toString()
        })
        .then(response => response.json())
        .then(data => {
            removeSetValue("recipient-name", data["FirstName"]);
            removeSetValue("recipient-surname", data["LastName"]);
            removeSetValue("dateOfBirth", data["DateOfBirth"]);
            removeSetValue("recipient-email", data["EmailAddress"]);
        })
        .catch(err => console.error(err));
}


function editPerson() {
    removeRedBorderId();
    if (document.forms["form1"]["fname"].value == "" || document.forms["form1"]["lname"].value == "" || document.forms["form1"]["formDate"].value == "" || document.forms["form1"]["email"].value == "") {
        fillAllFields();
    } else {
        // document.getElementById("recipient-submit").setAttribute("disabled","");

        let id = document.getElementById("recipient-submit").getAttribute("name");
        let firstName = document.getElementById("recipient-name").value;
        let lastName = document.getElementById("recipient-surname").value;
        let dateOfBirth = document.getElementById("dateOfBirth").value;
        let emailAddress = document.getElementById("recipient-email").value;

        const postParameters =  new URLSearchParams();
        postParameters.append("func", "updatePerson");
        postParameters.append("id", id);
        postParameters.append("FirstName", firstName);
        postParameters.append("LastName", lastName);
        postParameters.append("DateOfBirth", dateOfBirth);
        postParameters.append("EmailAddress", emailAddress);
        fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: postParameters.toString()
            })
            .then(response => response.json())
            .then(data => {
                checkResponse(data);
                getPerson(id);
            })
            .catch(err => console.error(err));
    }
}


function editOnclick(personId) {
    document.getElementById("recipient-submit").removeAttribute("disabled");
    document.getElementById("recipient-form").reset();

    document.getElementById("exampleModalLabel").firstChild.data = "Edit Client";

    document.getElementById("recipient-submit").firstChild.data = "Save";
    document.getElementById("recipient-submit").removeAttribute("onclick");
    document.getElementById("recipient-submit").setAttribute("onclick", "editPerson()");
    document.getElementById("recipient-submit").removeAttribute("name");
    document.getElementById("recipient-submit").setAttribute("name", personId);
    

    // document.getElementById("recipient-close").firstChild.data = "Cancel";

    getPerson(personId);
}


/**
 * 
 * @param {*} Row 
 * @param {string} id 
 * @returns 
 */
function createEditBtn(Row, id) {
    btnCell = document.createElement("button");
    btnCell.setAttribute("id", id);
    textBtn = document.createTextNode("Edit");
    btnCell.appendChild(textBtn);
    btnCell.setAttribute("class", "btn btn-primary btn-sm");
    btnCell.setAttribute("data-toggle", "modal");
    btnCell.setAttribute("data-target", "#exampleModal");
    btnCell.setAttribute("onclick", "editOnclick(this.id)");
    Row.appendChild(btnCell);
    return Row;
}


/**
 * Create an edit button for createTableFromObjects() row
 * @param {*} Row
 * @param {string} id
 * @param {string} fname
 * @param {string} lname
 * @returns 
 */
function createDeleteBtn(Row, id, fname, lname) {
    personText = fname.concat(" ", lname);
    btnCell = document.createElement("button");
    btnCell.setAttribute("id", id);
    textBtn = document.createTextNode("Delete");
    btnCell.appendChild(textBtn);
    att1 = document.createAttribute("class");
    att1.value = "btn btn-danger btn-sm";
    btnCell.setAttributeNode(att1);
    att2 = document.createAttribute("onclick");
    att2.value = "delSweetAlert(this.id, this.name)";
    btnCell.setAttributeNode(att2);
    btnCell.setAttribute("name", personText);
    Row.appendChild(btnCell);
    return Row;
}


/**
 * returns a table
 * @param {Array} data 
 * @returns 
 */
function createTableFromObjects(data) {
    table = document.createElement('table');
    table.setAttribute("id", "person");
    att = document.createAttribute("class");
    att.value = "table table-striped";
    table.setAttributeNode(att);
    tableBody = document.createElement('tbody');
    headerRow = document.createElement('tr');
    headerRow.setAttribute("id", "headerRow");

    // Create table header row
    colNames = ["First Name", "Last Name", "Date of birth", "Email address", "Age", ""];
    for (key of colNames) {
        headerCell = document.createElement('th');
        headerCell.textContent = key;
        headerRow.appendChild(headerCell);
    }
    tableBody.appendChild(headerRow);

    // Create table data rows
    if (Array.isArray(data) && data.length) {
        keys = Object.keys(data[0]);
        keys = keys.slice(1);
        for (obj of data) {
            dataRow = document.createElement('tr');
            for (key of keys) {
                dataCell = document.createElement('td');
                dataCell.textContent = obj[key];
                dataRow.appendChild(dataCell);
            }
            dataCell = document.createElement('td');
            createEditBtn(dataCell, obj["ID"]);
            createDeleteBtn(dataCell, obj["ID"], obj["FirstName"], obj["LastName"]);
            dataRow.appendChild(dataCell);
            tableBody.appendChild(dataRow);
        }
    } else {
        dataRow = document.createElement('tr');
        dataCell = document.createElement('td');
        dataCell.textContent = "Empty";
        dataCell.setAttribute("colspan","6");
        dataRow.appendChild(dataCell);
        tableBody.appendChild(dataRow);
    }

    table.appendChild(tableBody);
    return table;
}