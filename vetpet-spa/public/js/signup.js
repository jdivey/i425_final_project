//This function get called when the signup hash is clicked.
function signup() {
    $('.img-loading, main, .form-signin, #li-signin').hide();
    $('.form-signup, #li-signup').show();

    //window.location.hash = 'signup';
}

//submit the form to create a user account
$('form.form-signup').submit(async function (e) {
	$('.img-loading').show();
	e.preventDefault();

	//retrieve user details from the form
    let name = $('#signup-name').val();
    let email = $('#signup-email').val();
    let username = $('#signup-username').val();
    let password = $('#signup-password').val();

    try {
        //first create the user account,  need to await the function to be resolved
        const user = await createUser(name, email, username, password);

        //secondly, sign the user in, need to await the function to be resolved
        const result = await verifyUser(username, password);

        //hide the loading image
        $('.img-loading').hide();

        //update jwt and role
        jwt = result.jwt;
        role = result.role;

        //enable all links in nav bar, hide sign in and show signout links
        $('a.nav-link.disabled').removeClass('disabled');

        //display a confirmation message after a successful signup
        showMessage('Signup Message', 'Thank you for signing up.  ' +
            'Your account has been successfully created.  Please user the links to explore the site.');



    }catch (e) {
        showMessage("Signin Error", JSON.stringify(e.responseJSON, null, 4));
    }

});

//create a user account
function createUser(name, email, username, password) {
    const url = baseUrl_API + '/api/v1/users';

    return $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: {name: name, email: email, username: username, password: password}
    });
}