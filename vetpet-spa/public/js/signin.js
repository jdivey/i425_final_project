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
$('form.form-signin').submit(function (e) {
	e.preventDefault();
	let username = $('#signin-username').val();
	let password = $('#signin-password').val();

	//url to the api server
	const url = baseUrl_API + '/api/v1/users/authJWT';

	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'json',
		data: {username: username, password: password}
	}).done(function(data) {
		$('.img-loading').hide();
		jwt = data.jwt;
		role = data.role;

		//user has been successfully signed in, enable all the links in the nav bar, hide sign-in link and show sign-out link
		$('a.nav-link.disabled').removeClass('disabled'); //this code enables all the links in the nav bar
		$('li#li-signin').hide(); //this will hide the signin link
		$('li#li-signout').show(); //show the signout link
		showMessage('Signin Messsage', 'You have successfully signed in.  Click on the links in the nav bar to explore the site.');

	}).fail(function(jqXHR, textStatus) {
		showMessage('Signin Error', JSON.stringify(jqXHR.responseJSON, null, 4))
	}).always(function() {
		//run code regardless if request is successful or failed.
	})
});