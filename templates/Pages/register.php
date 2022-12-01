<?php
declare(strict_types=1);

?>
<div id="registerFormId">
    <form >
        <h1>Register</h1>
        <input type="email" placeholder="Email" name="email" id="email" aria-label="email" required/>
        <input type="password" placeholder="Password" name="password" src="password" aria-label="password" required/>
        <input type="password" placeholder="Repeat Password" name="repeatPassword" src="repeatPassword" aria-label="repeatPassword" required/>
        <button type="submit" id="button" value="Login">Register</button>
    </form>
</div>
<a href="javascript: hrefToLogin()" >login</a>
<script>

    // this function get the redirect login client
    function hrefToLogin() {
        window.location.href = JSON.parse(localStorage.getItem('login-link'))
    }

    // register
    const loginForm = document.querySelector("#registerFormId");
    loginForm.addEventListener("submit", (event) => {
        event.preventDefault()

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        fetch("https://php-oauth2.xel-localservices.nl/v1/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept" : "application/json",

            },
            body: JSON.stringify(data)
        }).then(async function (response) {

            if (!response.ok) {
                alert("Register failed");
            }
        })
            .catch(async error => console.log(error));
    });
</script>

