/***********************************************************************************************************
 ******                            Show Customers                                                   ******
 **********************************************************************************************************/
//This function shows all customers. It gets called when a user clicks on the Customer link in the nav bar.
function showCustomers() {
    console.log('show all the customers');
    //Constant of the url
    const url = baseUrl_API + '/api/v1/customers';

    $.ajax({
        url: url,
        headers: {"Authorization": "Bearer " + jwt}
    }).done(function (data)  {
        //display all the customers
        console.log(data);
        displayCustomers(data);
    }).fail(function(xhr, textStatus) {
        let err = {"Code": xhr.status, "Status": xhr.responseJSON.status};
        showMessage('Error', JSON.stringify(err, null, 4));
    });


}


//Callback function: display all customers; The parameter is an array of customer objects.
function displayCustomers(customers) {
    let _html;
    _html = `<div class='content-row content-row-header'>
        <div class='customer-id'>Customer ID</div>
        <div class='customer-first-name'>First Name</div>
        <div class='customer-last-name'>Last Name</div>
        </div>`;
    for (let x in customers) {
        let customer = customers[x];
        let cssClass = (x % 2 == 0) ? 'content-row' : 'content-row content-row-odd';
        _html += `<div id='content-row-${customer.customer_id}' class='${cssClass}'>
            <div class='customer-id'>
                <span class='list-key' data-customer='${customer.customer_id}' 
                     onclick=showCustomerPets('${customer.customer_id}') 
                     title='Get pets owned by the customers'>${customer.customer_id}
                </span>
            </div>
            <div class='customer-first-name'>${customer.first_name}</div>
            <div class='customer-last-name'>${customer.last_name}</div>
            </div>`;
    }

    console.log(customers);
    //Finally, update the page
    updateMain('Customers', 'All Customers', _html);
}


/***********************************************************************************************************
 ******                            Show Pets of Customers                              ******
 **********************************************************************************************************/
/* Display classes taught by a professor. It get called when a user clicks on a professor's name in
 * the professor list. The parameter is the professor's id.
*/
//Display customers of each pet in a modal
function showCustomerPets(customer_id) {
    //console.log('show a pet\'s classes');
    const id = $("span[data-customers=']" + customer_id + "']").html();
    const url = baseUrl_API + '/api/v1/customers/' + customer_id + '/pets';
    $.ajax({
        url: url,
        headers: {"Authorization": " Bearer " + jwt}
    }).done(function(pets) {
        displayPets(id, pets);
    }).fail(function(xhr) {
        let err = {"Code": xhr.status, "Status": xhr.responseJSON.status};
        showMessage('Error', JSON.stringify(err, null, 4));
    });
}




// Callback function that displays all pets owned by a customer
// Parameters: customer's name, an array of Pet objects
function displayPets(customer, pets) {
    let _html = "<div class='class'>No pets were found.</div>";
    if (pets.length > 0) {
        _html = "<table class='class'>" +
            "<tr>" +
            "<th class='pet-id'>Pet ID</th>" +
            "<th class='owner-id'>Owner ID</th>" +
            "<th class='pet-type'>Pet Type</th>" +
            "<th class='pet-breed'>Pet Breed</th>" +
            "<th class='pet-sex'>Pet Sex</th>" +
            "<th class='pet-birthday'>Pet Birthday</th>" +
            "<th class='pet-first-name'>First Name</th>" +
            "<th class='pet-last-name'>Last Name</th>" +
            "</tr>";

        for (let x in classes) {
            let aClass = classes[x];
            _html += "<tr>" +
                "<td class='pet-id'>" + aClass.pet_id + "</td>" +
                "<td class='owner-id'>" + aClass.owner_id + "</td>" +
                "<td class='pet-type'>" + aClass.pet_type + "</td>" +
                "<td class='pet-breed'>" + aClass.pet_breed + "</td>" +
                "<td class='pet-sex'>" + aClass.pet_sex + "</td>" +
                "<td class='pet-birthday'>" + aClass.pet_birthday + "</td>" +
                "<td class='pet-first-name'>" + aClass.first_name + "</td>" +
                "<td class='pet-last-name'>" + aClass.last_name + "</td>" +
                "</tr>"
        }
        _html += "</table>"
    }

    // set modal title and content
    $('#modal-title').html("Pets owned by " + customer);
    $('#modal-button-ok').hide();
    $('#modal-button-close').html('Close').off('click');
    $('#modal-content').html(_html);

    // Display the modal
    $('#modal-center').modal();
}