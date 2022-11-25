<?php
declare(strict_types=1);


?>
<div id="loginFormId">
    <form >
        <h1>Login</h1>
        <input type="email" placeholder="Email" name="username" id="username" aria-label="username" />
        <input type="password" placeholder="Password" name="password" src="password" aria-label="password"/>
        <button type="submit" class="btn" value="Login"> Sign in</button>
    </form>
</div>
<script>
    // login
    const loginForm = document.querySelector("#loginFormId");
    loginForm.addEventListener("submit", (event) => {
        event.preventDefault()

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        data["grant_type"] = "password";
        data["client_id"] = "00843";
        data["client_secret"] = "00843080de0839b3d29927e9c0881a51b2f359f4eeb7ab0f4b46b3abe7422934b1d3eb412e787ce5340769";
        fetch("https://php-oauth2.xel-localservices.nl/v1/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept" : "application/json"
            },
            body: JSON.stringify(data)
        }).then(async function (response) {
            console.log(await response.json())
            if (!response.ok) {
                alert("Login failed");
            }
        })
            .catch(async error => console.log(error));
    });
</script>

