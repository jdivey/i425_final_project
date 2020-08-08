<?php
/**
 * Author: Louie Zhu
 * Date: 6/1/2020
 * File: signin.php
 * Description: the signin form
 */
?>
<!--------- signin form ----------------------------------------------------------->
<form class="form-signin" style="display: none;">
    <!--    <input type="hidden" name="form-name" value="signin">-->
    <h1 class="h3 mb-3 font-weight-normal" style="padding: 20px; color: #FFFFFF; background-color: #d63a3a">Please
        sign in to PetVet</h1>
    <div style="width: 250px; margin: auto">
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="signin-username" class="form-control" placeholder="Username" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="signin-password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-danger btn-block" type="submit">Sign in</button>

        <p style="padding-top: 10px;">Don't have an account? <a id="vetpet-signup" href="#signup">Sign up</a></p>
    </div>
</form>