<?php
declare(strict_types=1);

?>
<div id="registerFormId">
    <form>
        <h1>Register</h1>
        <input type="email" placeholder="Email" name="email" id="email" aria-label="email" required/>
        <input type="password" placeholder="Password" name="password" id="password" minlength="8" aria-label="password" required/>
        <button type="submit" id="button" value="Login">Register</button>
    </form>
</div>
<a href="javascript: hrefToLogin()">login</a>
<script>

    // this function get the redirect login link of the client from the local storage
    function hrefToLogin() {
        window.location.href = JSON.parse(localStorage.getItem('login-link'))
    }


    // register
    const loginForm = document.querySelector("#registerFormId");
    loginForm.addEventListener("submit", (event) => {
        event.preventDefault()

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        fetch("/v1/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",

            },
            body: JSON.stringify(data)
        }).then(async function (response) {

            if (!response.ok) {
                alert("Register failed");
            }
            if (response.ok) {
                alert("Register succeeded");
                hrefToLogin();

            }
        })
            .catch(async error => console.log(error));
    });
</script>

