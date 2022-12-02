<?php
declare(strict_types=1);

?>
<div id="loginFormId">
    <form action="https://php-oauth2.xel-localservices.nl/v1/login" method="post">

        <h1>Login</h1>
        <input type="email" placeholder="Email" name="username" id="username" aria-label="username"/>
        <input type="password" placeholder="Password" name="password" src="password" aria-label="password"/>
        <input type="text" hidden aria-label="query_response_type" name="query_response_type" id="query_response_type"/>
        <input type="text" hidden aria-label="query_client_id" name="query_client_id" id="query_client_id"/>
        <input type="text" hidden aria-label="query_redirect_uri" name="query_redirect_uri" id="query_redirect_uri"/>
        <input type="text" hidden aria-label="query_scope" name="query_scope" id="query_scope"/>
        <input type="text" hidden aria-label="query_state" name="query_state" id="query_state"/>
        <button type="submit" class="btn" value="Login">Log in</button>
    </form>

</div>
<a href="/register" onclick="saveLoginLink()">register</a>
<script>

    // this function save the login url in the local storage
    function saveLoginLink() {
        localStorage.setItem('login-link', JSON.stringify(window.location.href));
    }

    ////get query param by the name
    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    /// this function fill the data from the query parameters in the body
    document.addEventListener("DOMContentLoaded", function (event) {
        console.log("DOM fully loaded and parsed");

        document.getElementById('query_client_id').value = getParameterByName('client_id');
        document.getElementById('query_response_type').value = getParameterByName('response_type');
        document.getElementById('query_redirect_uri').value = getParameterByName('redirect_uri');
        document.getElementById('query_scope').value = getParameterByName('scope');
        document.getElementById('query_state').value = getParameterByName('state');

    });
</script>

