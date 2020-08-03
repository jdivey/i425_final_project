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
    pets = response;
    console.log(response);
    pets.forEach(function(pet, x){

        let cssClass = (x % 2 == 0) ? 'content-row' : 'content-row content-row-odd';
        _html += "<div class='" + cssClass + "'>" +
            "<div class='pet-id'>" +
            "<span class='list-key' onclick=showPet('" + pet.pet_id + "') title='Get pet details'>" + pet.pet_id + "</span>" +
            "</div>" +
            "<div class='owner-id'>" + pet.owner_id + "</div>" +
            "<div class='pet-type'>" + pet.pet_type + "</div>" +
            "<div class='pet-breed'>" + pet.pet_breed + "</div>";
    });

    console.log(pets);
    //Finally, update the page
    updateMain('Pets', 'All Pets', _html);
}


/***********************************************************************************************************
 ******                            Show Details of a Pet                                           ******
 **********************************************************************************************************/
/* Display a pet's details. It get called when a user clicks on a course's number in
 * the pet list. The parameter is the pet id.
*/
function showPet(customer_id) {
    console.log('get pet details');
    const url = baseUrl_API + '/api/v1/customers/' + customer_id + '/pets';
    $.ajax({
        url: url,
        headers: {"Authorization": " Bearer " + jwt}
    }).done(function(pets) {
        displayPets(customer_id, pets);
    }).fail(function(xhr) {
        let err = {"Code": xhr.status, "Status": xhr.responseJSON.status};
        showMessage('Error', JSON.stringify(err, null, 4));
    });
}


// Callback function that displays all details of a course.
// Parameters: course number, a promise
function displayPet(pet_id, response) {
    let _html;
    pet = response.data;
    _html =
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet ID</div><div class='course-detail-field'>" + pet.pet_id + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Owner ID</div><div class='course-detail-field'>" + pet.owner_id + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet Type</div><div class='course-detail-field'>" + pet.pet_type + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet Breed</div><div class='course-detail-field'>" + pet.pet_breed + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet Sex</div><div class='course-detail-field'>" + pet.pet_sex + "</div></div>";

    $('#pet-detail-' + pet_id).html(_html);
    $("[id^='pet-detail-']").each(function(){   //hide the visible one
        $(this).not("[pet_id*='" + pet_id + "']").hide();
    });

    $('#pet-detail-' + pet_id).toggle();
}
