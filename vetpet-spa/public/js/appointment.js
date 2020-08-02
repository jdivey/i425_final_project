/***********************************************************************************************************
 ******                            CRUD Appointments                                                     ******
 **********************************************************************************************************/

//This function gets called when the Student link in the nav bar is clicked. It shows all the records of students
function showAppointments() {
	console.log('Show all appointments');
    //Constant of the url
    const url = baseUrl_API + '/api/v1/appointments';

    $.ajax({
        url: url,
        headers: {"Authorization": "Bearer " + jwt}
    }).done(function (data)  {
        //display all the appointments
        displayAppointments(data);
    }).fail(function(xhr, textStatus) {
        let err = {"Code": xhr.status, "Status": xhr.responseJSON.status};
        showMessage('Error', JSON.stringify(err, null, 4));
    });
}


//Callback function that shows all the appointments. The parameter is an array of appointments.
// The first parameter is an array of students and second parameter is the subheading, defaults to null.
function displayAppointments(appointments, subheading=null) {
    // search box and the row of headings
    let _html = `<div style='text-align: right; margin-bottom: 3px'>
            <input id='search-term' placeholder='Enter search terms'> 
            <button id='btn-appointment-search' onclick='searchAppointments()'>Search</button></div>
            <div class='content-row content-row-header'>
            <div class='appointment-id'>Appointment ID</div>
            <div class='pet-id'>Pet ID</div>
            <div class='appointment-status'>Appointment Status</div>
            <div class='vet-id'>Vet ID</div>
            </div>`;  //end the row

    // content rows
    for (let x in appointments) {
        let appointment = appointments[x];
        _html += `<div class='content-row'>
            <div class='appointment-id'>${appointment.appointment_id}</div>
            <div class='pet-id' id='student-edit-name-${appointment.appointment_id}'>${appointment.pet_id}</div> 
            <div class='appointment-status' id='student-edit-email-${appointment.appointment_id}'>${appointment.appointment_status}</div>
            <div class='vet-id' id='student-edit-major-${appointment.appointment_id}'>${appointment.vet_id}</div>`;
        if (role == 1) {
            _html += `<div class='list-edit'><button id='btn-appointment-edit-${appointment.id}' onclick=editAppointment('${appointment.id}') class='btn-light'> Edit </button></div>
            <div class='list-update'><button id='btn-appointment-update-${appointment.id}' onclick=updateAppointment('${appointment.id}') class='btn-light btn-update' style='display:none'> Update </button></div>
            <div class='list-delete'><button id='btn-appointment-delete-${appointment.id}' onclick=deleteAppointment('${appointment.id}') class='btn-light'>Delete</button></div>
            <div class='list-cancel'><button id='btn-appointment-cancel-${appointment.id}' onclick=cancelUpdateAppointment('${appointment.id}') class='btn-light btn-cancel' style='display:none'>Cancel</button></div>`
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

	console.log(appointments);
    //Finally, update the page
    subheading = (subheading == null) ? 'All Appointments' : subheading;
    updateMain('Appointments', subheading, _html);
}

/***********************************************************************************************************
 ******                            Search Appointments                                                   ******
 **********************************************************************************************************/
function searchAppointments() {
   console.log('searching for appointments');
}


/***********************************************************************************************************
 ******                            Edit Appointment                                                       ******
 **********************************************************************************************************/

// This function gets called when a user clicks on the Edit button to make items editable
function editAppointment(id) {
    //Reset all items
    resetAppointment();

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
 ******                            Delete Appointment                                                     ******
 **********************************************************************************************************/

// This function confirms deletion of an appointment. It gets called when a user clicks on the Delete button.
function deleteAppointment(id) {
    $('#modal-button-ok').html("Delete").show().off('click').click(function () {
        removeAppointment(id);
    });
    $('#modal-button-close').html('Cancel').show().off('click');
    $('#modal-content').html('Are you sure you want to delete the appointment?');

    // Display the modal
    $('#modal-center').modal();
}

// Callback function that removes an appointment from the system. It gets called by the deleteAppointment function.
function removeAppointment(id) {
	console.log('remove the appointment whose id is ' + id);
}


/***********************************************************************************************************
 ******                            Add Appointment                                                        ******
 **********************************************************************************************************/
//This function shows the row containing editable fields to accept user inputs.
// It gets called when a user clicks on the Add New Appointment link
function showAddRow() {
    resetAppointment(); //Reset all items
    $('div#appointment-add-row').show();
}

//This function inserts a new appointment. It gets called when a user clicks on the Insert button.
function addAppointment() {
	console.log('Add a new appointment');
}



// This function cancels adding a new appointment. It gets called when a user clicks on the Cancel button.
function cancelAddAppointment() {
    $('#appointment-add-row').hide();
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
 ******                            Reset appointment section                                             ******
 **********************************************************************************************************/
//Reset appointment section: remove editable features, hide update and cancel buttons, and display edit and delete buttons
function resetAppointment() {
    // Remove the editable feature from all divs
    $("div[id^='appointment-edit-']").each(function () {
        $(this).removeAttr('contenteditable').removeClass('appointment-editable');
    });

    // Hide all the update and cancel buttons and display all the edit and delete buttons
    $("button[id^='btn-appointment-']").each(function () {
        const id = $(this).attr('id');
        if (id.indexOf('update') >= 0 || id.indexOf('cancel') >= 0) {
            $(this).hide();
        } else if (id.indexOf('edit') >= 0 || id.indexOf('delete') >= 0) {
            $(this).show();
        }
    });
}