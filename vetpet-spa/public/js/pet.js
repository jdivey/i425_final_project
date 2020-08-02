/***********************************************************************************************************
 ******                            Show Pets                                                     ******
 **********************************************************************************************************/
//This function shows all pets. It gets called when a user clicks on the Pet link in the nav bar.
function showPets () {
	console.log('show all pets');
    //Constant of the url
    const url = baseUrl_API + '/api/v1/pets';

    $.ajax({
        url: url,
        headers: {"Authorization": "Bearer " + jwt}
    }).done(function (data)  {
        //display all the pets
        displayPets(data);
    }).fail(function(xhr, textStatus) {
        let err = {"Code": xhr.status, "Status": xhr.responseJSON.status};
        showMessage('Error', JSON.stringify(err, null, 4));
    });
}


//Callback function: display all pets; The parameter is a promise returned by axios request.
function displayPets(response) {
    let _html;
    _html =
        "<div class='content-row content-row-header'>" +
        "<div class='pet-id'>Pet ID</></div>" +
        "<div class='owner-id'>Owner ID</></div>" +
        "<div class='pet-type'>Pet Type</div>" +
        "<div class='pet-breed'>Pet Breed</div>" +
        "<div class='pet-sex'>Pet Sex</div>" +
        "<div class='pet-birthday'>Pet Birthday</div>" +
        "<div class='pet-first-name'>First Name</div>" +
        "<div class='pet-last-name'>Last Name</div>" +
        "</div>";
    pets = response.data;
    pets.forEach(function(pet, x){
        let cssClass = (x % 2 == 0) ? 'content-row' : 'content-row content-row-odd';
        _html += "<div class='" + cssClass + "'>" +
            "<div class='pet-id'>" +
            "<span class='list-key' onclick=showPet('" + pet.id + "') title='Get pet details'>" + pet.id + "</span>" +
            "</div>" +
            "<div class='owner-id'>" + pet.owner_id + "</div>" +
            "<div class='pet-type'>" + pet.pet_type + "</div>" +
            "<div class='pet-breed'>" + pet.pet_breed + "</div>" +
            "</div>";
    });

    //Finally, update the page
    updateMain('Pets', 'All Pets', _html);
}


/***********************************************************************************************************
 ******                            Show Details of a Course                                           ******
 **********************************************************************************************************/
/* Display a course's details. It get called when a user clicks on a course's number in
 * the course list. The parameter is the course number.
*/
function showPet(id) {
    console.log('get course classes');
}


// Callback function that displays all details of a course.
// Parameters: course number, a promise
function displayCourse(number, response) {
    let _html;
    course = response.data;
    _html =
        "<div class='course-detail-row'><div class='course-detail-label'>Number</div><div class='course-detail-field'>" + course.number + "</div></div>" +
        "<div class='course-detail-row'><div class='course-detail-label'>Title</div><div class='course-detail-field'>" + course.title + "</div></div>" +
        "<div class='course-detail-row'><div class='course-detail-label'>Credit Hours</div><div class='course-detail-field'>" + course.credit_hours + "</div></div>" +
        "<div class='course-detail-row'><div class='course-detail-label'>Prerequisties</div><div class='course-detail-field'>" + course.prerequisites + "</div></div>" +
        "<div class='course-detail-row'><div class='course-detail-label'>Description</div><div class='course-detail-field'>" + course.description + "</div></div>";

    $('#course-detail-' + number).html(_html);
    $("[id^='course-detail-']").each(function(){   //hide the visible one
        $(this).not("[id*='" + number + "']").hide();
    });

    $('#course-detail-' + number).toggle();
}
