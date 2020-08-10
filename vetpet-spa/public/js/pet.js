/***********************************************************************************************************
 ******                            Show Pets                                                     ******
 **********************************************************************************************************/
//This function shows all pets. It gets called when a user clicks on the Pet link in the nav bar.
function showPets (offset = 0) {

    let limit = ($('#pet-limit-select').length) ? $('#pet-limit-select option:checked').val() : 5;
    let sort = ($('#pet-sort-select').length) ? $('#pet-sort-select option:checked').val() : 'pet_id:asc';
    //construct the url that includes limit, offset, and sort variables
    let url = baseUrl_API + '/api/v1/pets?limit=' + limit + "&offset=" + offset + "&sort=" + sort;

    axios({
        method: 'get',
        url: url,
        cache: true,
        headers: {"Authorization": "Bearer" + jwt}
    }).then(function(response) {

        displayPets(response.data);
    }).catch(function(error) {
        handleAxiosError(error);
    })
}


//Callback function: display all pets; The parameter is a promise returned by axios request.
function displayPets(response) {
    console.log(response);

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
    console.log(pets);
    pets.forEach(function(pet, x){
        let cssClass = (x % 2 == 0) ? 'content-row' : 'content-row content-row-odd';
        _html += "<div class='" + cssClass + "'>" +
            "<div class='pet-id'>" +
            "<span class='list-key' onclick=showPet('" + pet.pet_id + "') title='Get pet details'>" + pet.pet_id + "</span>" +
            "</div>" +
            "<div class='owner-id'>" + pet.owner_id + "</div>" +
            "<div class='pet-type'>" + pet.pet_type + "</div>" +
            "<div class='pet-breed'>" + pet.pet_breed + "</div>" +
            "<div class='pet-sex'>" + pet.pet_sex + "</div>" +
            "<div class='pet-birthday'>" + pet.pet_birthday + "</div>" +
            "<div class='pet-first-name'>" + pet.first_name + "</div>" +
            "<div class='pet-last-name'>" + pet.last_name + "</div>" +
            "</div>" +
            "<div class='container pet-detail' id='pet-detail-" + pet.pet_id + "' style='display: none'></div>";
    });

    //add a div block for pagination links and selection lists for limiting and sorting courses
    _html += "<div class = 'content-row pet-pagination'></div>";

    //pagination
    _html += paginatePets(response);

    //limit pets
    _html += limitPets(response);

    //sort pets
    _html += sortPets(response);

    //close the div blocks
    _html += "<div></div>";

    //Finally, update the page
    updateMain('Pets', 'All Pets', _html);
}


/***********************************************************************************************************
 ******                            Show Details of a Pet                                           ******
 **********************************************************************************************************/
/* Display a pet's details. It get called when a user clicks on a course's number in
 * the pet list. The parameter is the pet id.
*/
function showPet(pet_id) {
    console.log('get pet details');
    let url = baseUrl_API + '/api/v1/pets/' + pet_id;

    axios({
        method: 'get',
        url: url,
        cache: true,
        headers: {"Authorization": "Bearer" + jwt}
    }).then(function(response) {
        displayPet(pet_id, response);
    }).catch(function(error) {
        handleAxiosError(error);
    })
}


// Callback function that displays all details of a course.
// Parameters: course number, a promise
function displayPet(pet_id, response) {
    let _html;
    pet = response.data;
    _html =
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet ID</div><div class='pet-detail-field'>" + pet.pet_id + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Owner ID</div><div class='pet-detail-field'>" + pet.owner_id + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet Type</div><div class='pet-detail-field'>" + pet.pet_type + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet Breed</div><div class='pet-detail-field'>" + pet.pet_breed + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet Sex</div><div class='pet-detail-field'>" + pet.pet_sex + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Pet Birthday</div><div class='pet-detail-field'>" + pet.pet_birthday + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>First Name</div><div class='pet-detail-field'>" + pet.first_name + "</div></div>" +
        "<div class='pet-detail-row'><div class='pet-detail-label'>Last Name</div><div class='pet-detail-field'>" + pet.last_name + "</div></div>";

    $('#pet-detail-' + pet_id).html(_html);
    $("[id^='pet-detail-']").each(function(){   //hide the visible one
        $(this).not("[id*='" + pet_id + "']").hide();
    });

    $('#pet-detail-' + pet_id).toggle();
}

/*************************************
 * This function handles errors occurred by an AXIOS request
 * *********************************
 */

function handleAxiosError(error) {

    let errMessage;
    if (error.response) {
        ////the request was made and the server responded with a status code of 400 or 500.
        errMessage = {"Code": error.response.status, "Status": error.response.data.status};
    } else if(error.request) {
        //the request was made but no response was received
        errMessage = {"Code": error.request.status, "Status": error.request.data.status};
    }else {
        //something happened in setting up the request that triggered an error
        errMessage = JSON.stringify(error.message, null, 4);
    }

    showMessage('Error', errMessage);
}


//Pagination, sorting and limiting pets ****************************************************

//paginate pets
function paginatePets(response) {
    //calculate total of pages
    let limit = response.limit;
    let totalCount = response.totalCount;
    let totalPages = Math.ceil(totalCount/limit);

    //determine the current page showing
    let offset = response.offset;
    let currentPage = offset/limit + 1;

    //retrieve the array of links from the response JSON document
    let links = response.links;

    //convert an array of links to JSON document.  Keys are "self", "prev", "next", "first", "last", values are offsets
    let pages = {};

    //extract offset from each link and store it in pages
    links.forEach(function(link) {
        let href= link.href;
        let offset = href.substr(href.indexOf('offset') + 7);
        pages[link.rel] = offset;
    });

    if(!pages.hasOwnProperty('prev')) {
        pages.prev = pages.self;

    }

    if(!pages.hasOwnProperty('next')) {
        pages.next = pages.self;
    }

    //generate html code for links
    let _html = `Showing Page ${currentPage} of ${totalPages}&nbsp;&nbsp;&nbsp;&nbsp;
            <a href='#pet' title="first page" onclick= 'showPets(${pages.first})'> << </a>
            <a href='#pet' title="previous page" onclick= 'showPets(${pages.prev})'> < </a>
            <a href='#pet' title="next page" onclick= 'showPets(${pages.next})'> > </a>
            <a href='#pet' title="last page" onclick= 'showPets(${pages.last})'> >> </a>`;

    return _html;

}

//limit pets
function limitPets(response) {
    //define an array of pets per page options
    let petsPerPageOptions = [5, 10, 20];

    //create a selection list for limiting pets
    let _html = `&nbsp;&nbsp;&nbsp;&nbsp; Items per page:<select id = 'pet-limit-select' onChange='showPets()'>`;
    petsPerPageOptions.forEach(function(option) {
        let selected = (response.limit == option) ? "selected" : "";
        _html += `<option ${selected} value="${option}">${option}</option>`;
    })

    _html += "</select>";

    return _html;

}

//sort pets
function sortPets(response) {
    //create the selection list for sorting
    let sort = response.sort;

    //sort field and direction, covert json to a string, then remove {,}, and "
    let sortString = JSON.stringify(sort).replace(/["{}]+/g, "");

    //define a JSON containing sort options
    let sortOptions = {"number:asc": "Number A - Z",
        "number:desc": "Number Z - A",
        "title:asc": "Title A - Z",
        "title:desc": "Title Z - A"
    };

    //create selection list
    let _html = `&nbsp;&nbsp;&nbsp;&nbsp; Sort by: <select id='pet-sort-select' onChange="showPets()">`;
    for(let options in sortOptions) {
        let selected = (option == sortString) ? "selected" : "";
        _html += `<option ${selected} value="${option}">${sortOptions[option]}</option>`
    }
    _html += "</select>";
    return _html;
}