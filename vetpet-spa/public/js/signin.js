//global variables
var jwt = '';   //JSON Web token
var role = '';  //user's role: 1 for admins and 2 for regular users

//This function get called when the signin hash is clicked.
function signin() {
    $('main, .form-signup, #li-signout, #li-signup').hide();
    $('.form-signin, #li-signin').show();
    $("li#li-customer > a, li#li-pet > a, li#li-appointment > a").addClass('disabled');

    //remove these two lines once the lab is done
    $('#signin-username').val('jdivey');
    $('#signin-password').val('password');
}

//submit username and password to be verified by the API server
$('form.form-signin').submit(async function (e) {
	e.preventDefault();
	let username = $('#signin-username').val();
	let password = $('#signin-password').val();
    try {
        const result = await verifyUser(username, password);

        //hide the loading image
        $('.img-loading').hide();

        //update jwt and role
        jwt = result.jwt;
        role = result.role;

        //enable all links in nav bar, hide sign in and show signout links
        $('a.nav-link.disabled').removeClass('disabled');

        //display a message after a successful signin
        showMessage("Signin Message", "You have successfully signed in.  Click on the links in the nav bar to explore the site.");
    }catch (e) {
        showMessage("Signin Error", JSON.stringify(e.responseJSON, null, 4))
    }
});

//verify username and password
function verifyUser(username, password) {
    //url to the api server
    const url = baseUrl_API + '/api/v1/users/authJWT';

    return $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {username: username, password: password}
    })
}