/***********************************************************************************************************
 ******                            CRUD Students                                                      ******
 **********************************************************************************************************/

//This function gets called when the Student link in the nav bar is clicked. It shows all the records of students
function showStudents() {
	console.log('Show all students');
}


//Callback function that shows all the students. The parameter is an array of students.
// The first parameter is an array of students and second parameter is the subheading, defaults to null.
function displayStudents(students, subheading=null) {
    // search box and the row of headings
    let _html = `<div style='text-align: right; margin-bottom: 3px'>
            <input id='search-term' placeholder='Enter search terms'> 
            <button id='btn-student-search' onclick='searchStudents()'>Search</button></div>
            <div class='content-row content-row-header'>
            <div class='student-id'>Student ID</div>
            <div class='student-name'>Name</div>
            <div class='student-email'>Email</div>
            <div class='student-major'>Major</div>
            <div class='student-gpa'>GPA</div>
            </div>`;  //end the row

    // content rows
    for (let x in students) {
        let student = students[x];
        _html += `<div class='content-row'>
            <div class='student-id'>${student.id}</div>
            <div class='student-name' id='student-edit-name-${student.id}'>${student.name}</div> 
            <div class='student-email' id='student-edit-email-${student.id}'>${student.email}</div>
            <div class='student-major' id='student-edit-major-${student.id}'>${student.major}</div> 
            <div class='student-gpa' id='student-edit-gpa-${student.id}'>${student.gpa}</div>`;
        if (role == 1) {
            _html += `<div class='list-edit'><button id='btn-student-edit-${student.id}' onclick=editStudent('${student.id}') class='btn-light'> Edit </button></div>
            <div class='list-update'><button id='btn-student-update-${student.id}' onclick=updateStudent('${student.id}') class='btn-light btn-update' style='display:none'> Update </button></div>
            <div class='list-delete'><button id='btn-student-delete-${student.id}' onclick=deleteStudent('${student.id}') class='btn-light'>Delete</button></div>
            <div class='list-cancel'><button id='btn-student-cancel-${student.id}' onclick=cancelUpdateStudent('${student.id}') class='btn-light btn-cancel' style='display:none'>Cancel</button></div>`
        }
        _html += '</div>';  //end the row
    }

    //the row of element for adding a new student
	if (role == 1) {
        _html += `<div class='content-row' id='student-add-row' style='display: none'> 
            <div class='student-id student-editable' id='student-new-id' contenteditable='true'></div>
            <div class='student-name student-editable' id='student-new-name' contenteditable='true'></div>
            <div class='student-email student-editable' id='student-new-email' contenteditable='true'></div>
            <div class='student-major student-editable' id='student-new-major' contenteditable='true'></div>
            <div class='student-gpa student-editable' id='student-new-gpa' contenteditable='true'></div>
            <div class='list-update'><button id='btn-add-student-insert' onclick='addStudent()' class='btn-light btn-update'> Insert </button></div>
            <div class='list-cancel'><button id='btn-add-student-cancel' onclick='cancelAddStudent()' class='btn-light btn-cancel'>Cancel</button></div>
            </div>`;  //end the row

        // add new student button
        _html += `<div class='content-row student-add-button-row'><div class='student-add-button' onclick='showAddRow()'>+ ADD NEW STUDENT</div></div>`;
    }
    //Finally, update the page
    subheading = (subheading == null) ? 'All Students' : subheading;
    updateMain('Students', subheading, _html);
}

/***********************************************************************************************************
 ******                            Search Students                                                    ******
 **********************************************************************************************************/
function searchStudents() { 
   console.log('searching for students'); 
}


/***********************************************************************************************************
 ******                            Edit Student                                                       ******
 **********************************************************************************************************/

// This function gets called when a user clicks on the Edit button to make items editable
function editStudent(id) {
    //Reset all items
    resetStudent();

    //select all divs whose ids begin with 'student' and end with the current id and make them editable
    $("div[id^='student-edit'][id$='" + id + "']").each(function () {
        $(this).attr('contenteditable', true).addClass('student-editable');
    });

    $("button#btn-student-edit-" + id + ", button#btn-student-delete-" + id).hide();
    $("button#btn-student-update-" + id + ", button#btn-student-cancel-" + id).show();
    $("div#student-add-row").hide();
}

//This function gets called when the user clicks on the Update button to update a student record
function updateStudent(id) {
	console.log('update the student whose id is ' + id);
}


//This function gets called when the user clicks on the Cancel button to cancel updating a student
function cancelUpdateStudent(id) {
    showStudents();
}

/***********************************************************************************************************
 ******                            Delete Student                                                     ******
 **********************************************************************************************************/

// This function confirms deletion of a student. It gets called when a user clicks on the Delete button.
function deleteStudent(id) {
    $('#modal-button-ok').html("Delete").show().off('click').click(function () {
        removeStudent(id);
    });
    $('#modal-button-close').html('Cancel').show().off('click');
    $('#modal-content').html('Are you sure you want to delete the student?');

    // Display the modal
    $('#modal-center').modal();
}

// Callback function that removes a student from the system. It gets called by the deleteStudent function.
function removeStudent(id) {
	console.log('remove the student whose id is ' + id);
}


/***********************************************************************************************************
 ******                            Add Student                                                        ******
 **********************************************************************************************************/
//This function shows the row containing editable fields to accept user inputs.
// It gets called when a user clicks on the Add New Student link
function showAddRow() {
    resetStudent(); //Reset all items
    $('div#student-add-row').show();
}

//This function inserts a new student. It gets called when a user clicks on the Insert button.
function addStudent() {
	console.log('Add a new student');
}



// This function cancels adding a new student. It gets called when a user clicks on the Cancel button.
function cancelAddStudent() {
    $('#student-add-row').hide();
}

/***********************************************************************************************************
 ******                            Check Fetch for Errors                                             ******
 **********************************************************************************************************/
/* This function checks fetch request for error. When an error is detected, throws an Error to be caught
 * and handled by the catch block. If there is no error detetced, returns the promise.
 * Need to use async and await to retrieve JSON object when an error has occurred.
 */
let checkFetch = async function (response) {
    if (!response.ok) {
        await response.json()  //need to use await so Javascipt will until promise settles and returns its result
            .then(result => {
                throw Error(JSON.stringify(result, null, 4));
            });
    }
    return response;
}


/***********************************************************************************************************
 ******                            Reset student section                                             ******
 **********************************************************************************************************/
//Reset student section: remove editable features, hide update and cancel buttons, and display edit and delete buttons
function resetStudent() {
    // Remove the editable feature from all divs
    $("div[id^='student-edit-']").each(function () {
        $(this).removeAttr('contenteditable').removeClass('student-editable');
    });

    // Hide all the update and cancel buttons and display all the edit and delete buttons
    $("button[id^='btn-student-']").each(function () {
        const id = $(this).attr('id');
        if (id.indexOf('update') >= 0 || id.indexOf('cancel') >= 0) {
            $(this).hide();
        } else if (id.indexOf('edit') >= 0 || id.indexOf('delete') >= 0) {
            $(this).show();
        }
    });
}