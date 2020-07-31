/***********************************************************************************************************
 ******                            Show Pets                                                     ******
 **********************************************************************************************************/
//This function shows all pets. It gets called when a user clicks on the Pet link in the nav bar.
function showPets () {
	console.log('show all pets');
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
    courses = response.data;
    courses.forEach(function(course, x){
        let cssClass = (x % 2 == 0) ? 'content-row' : 'content-row content-row-odd';
        _html += "<div class='" + cssClass + "'>" +
            "<div class='course-number'>" +
            "<span class='list-key' onclick=showCourse('" + course.number + "') title='Get class details'>" + course.number + "</span>" +
            "</div>" +
            "<div class='course-title'>" + course.title + "</div>" +
            "<div class='course-hour'>" + course.credit_hours + "</div>" +
            "<div class='course-prerequisite'>" + course.prerequisites + "</div>" +
            "</div>" +
            "<div class='container course-detail' id='course-detail-" + course.number + "' style='display: none'></div>";
    });

    //Finally, update the page
    updateMain('Courses', 'All Courses', _html);
}


/***********************************************************************************************************
 ******                            Show Details of a Course                                           ******
 **********************************************************************************************************/
/* Display a course's details. It get called when a user clicks on a course's number in
 * the course list. The parameter is the course number.
*/
function showCourse(number) {
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
