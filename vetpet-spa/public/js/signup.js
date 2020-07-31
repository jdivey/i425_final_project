//This function get called when the signup hash is clicked.
function signup() {
    $('.img-loading, main, .form-signin, #li-signin').hide();
    $('.form-signup, #li-signup').show();

    //window.location.hash = 'signup';
}

//submit the form to create a user account
$('form.form-signup').submit(function (e) {
	$('.img-loading').show();
	e.preventDefault();

	//retrieve user details from the form
    let name = $('#signup-name').val();
    let email = $('#signup-email').val();
    let username = $('#signup-username').val();
    let password = $('#signup-password').val();

    const url = baseUrl_API + '/api/v1/users';

    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: {name: name, email: email, username: username, password: password}
    }).done(function () {
        $('.img-loading').hide();

        //display a confirmation message after a successful signup
        showMessage('Signup Message', 'Thank you for signing up.  ' +
            'Your account has been successfully created.  Please sign in now to explore the site.');

        $('li#li-signin').show(); //show the signin link
        $('li#li-signout').hide(); // hide the signout line
    }).fail(function (jqXHR, textStatus) {
        showMessage('Signup Error', JSON.stringify(jqXHR.responseJSON, null, 4))
    }).always(function() {

    })
});