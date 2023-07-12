function deletePerson() {
    const postParameters =  new URLSearchParams();
    postParameters .set("func", "deletePerson");
    fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: postParameters.toString()
        })
        .then(response => {
            response.json();
            console.log(response);
        })
        .then(loadAllPeople())
        .catch(err => console.error(err));
}

function deleteAll() {
    const postParameters =  new URLSearchParams();
    postParameters .set("func", "deleteAllPeople");
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
    // console.log(postParameters.toString());
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
        })
        .catch(err => console.error(err));
}

function loadAllPeople() {
    fetch('http://localhost:3000/foundation_tasks/task(6)/fetch.php?type=all')
        .then(response => response.json())
        .then(data => document.getElementById("person").replaceWith(createTableFromObjects(data)))
        .catch(err => console.error(err));
}

/**
 * Returns an edit button
 * @param {*} Row 
 * @returns 
 */
function createEditBtn(Row, id) {
    btnCell = document.createElement("a");
    btnCell.setAttribute("id", id);
    textBtn = document.createTextNode("Edit");
    btnCell.appendChild(textBtn);
    att1 = document.createAttribute("class");
    att1.value = "btn btn-primary btn-sm";
    btnCell.setAttributeNode(att1);
    att2 = document.createAttribute("href");
    att2.value = "edit_person.html";
    btnCell.setAttributeNode(att2);
    Row.appendChild(btnCell);
    return Row;
}

/**
 * Returns a delete button
 * @param {*} Row 
 * @returns
 */
function createDeleteBtn(Row, id) {
    btnCell = document.createElement("a");
    btnCell.setAttribute("id", id);
    textBtn = document.createTextNode("Delete");
    btnCell.appendChild(textBtn);
    att1 = document.createAttribute("class");
    att1.value = "btn btn-danger btn-sm";
    btnCell.setAttributeNode(att1);
    att2 = document.createAttribute("onclick");
    att2.value = "deletePerson()";
    btnCell.setAttributeNode(att2);
    Row.appendChild(btnCell);
    return Row;
}

/**
 * returns a table
 * @param {*} data 
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
        for (obj of data) {
            dataRow = document.createElement('tr');
            for (key of keys) {
                dataCell = document.createElement('td');
                dataCell.textContent = obj[key];
                dataRow.appendChild(dataCell);
            }
            dataCell = document.createElement('td');
            createEditBtn(dataCell, obj["EmailAddressStr"]);
            createDeleteBtn(dataCell, obj["EmailAddressStr"]);
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