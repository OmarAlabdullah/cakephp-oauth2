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

    //get query param
    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    // login
    const loginForm = document.querySelector("#loginFormId");
    loginForm.addEventListener("submit", (event) => {
        event.preventDefault()

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        // send query parameters in body data to the backend
        data["query_response_type"] = getParameterByName('response_type');
        data["query_redirect_uri"] = getParameterByName('redirect_uri');
        data["query_scope"] = getParameterByName('scope');
        data["query_client_id"] = getParameterByName('client_id');

        fetch("https://php-oauth2.xel-localservices.nl/v1/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept" : "application/json",
                "Access-Control-Allow-Origin" : "*"
            },
            body: JSON.stringify(data)
        }).then(async function (response) {

            if (!response.ok) {
                alert("Login failed");
            }
            if (response.ok){

            }
            if (response.redirected) {
                window.location.href = response.url;
            }
        })
            .catch(async error => console.log(error));
    });
</script>

