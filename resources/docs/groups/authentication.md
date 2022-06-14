# Authentication


## Signup




> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/auth/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"name":"Mohamed Ahmed","firstname":"Mohamed","lastname":"Ahmed","email":"user@example.com","phone":"+201065987456","firebase_jwt_token":"minus","otp":"esse","password":"445566332255","password_confirmation":"et"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "name": "Mohamed Ahmed",
    "firstname": "Mohamed",
    "lastname": "Ahmed",
    "email": "user@example.com",
    "phone": "+201065987456",
    "firebase_jwt_token": "minus",
    "otp": "esse",
    "password": "445566332255",
    "password_confirmation": "et"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "token": {
        "token_type": "Bearer",
        "expires_in": 31536000,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiMTJiZmUxYWM4ZmY0YzhjOWZmNjhhZDJlYTAxZGRkMzQ3YjM0YzViZjQyNDc5NTQ5MjAwMDhjOTI4ZGEwYTlhZmI4OTg1M2U0N2Y3MzQxMTkiLCJpYXQiOjE2MDcyNzk4NzEsIm5iZiI6MTYwNzI3OTg3MSwiZXhwIjoxNjM4ODE1ODcxLCJzdWIiOiIxMiIsInNjb3BlcyI6WyIqIl19.OeVAZ6oN0Eat_pm1oT-ZCYq4fyAnjOPMJ9WtFIl9mkZdiVtQC-5Dz_-sRVdXEhRzU4Zjf6qxpSlPjJb5NmPaEoHfLQHaFEStdBXwuWi9PdRCTLmBvP51UT-fTSpMXQC0CIQTAQb7KeHjhdhW4TX2BKimvMjPeqBGDfxHai71gMEMKGLW7BcYETWEFTdA8MCc9FdalBuJqqtfoa2U90QZxR-1HGqq5Pbl9-O3xv45fBBR3TDFm9f5JbBcA6pSbFbIbwOk_kZN5BGCYEBR3Lh1ILSGnuz-qjQjrD5E2Qio4ji51L7bzRRGM-pTgTtCDORKtiC4xUHyCcZ3g6V801tUUibRYTOQK3DBxuGy10ynCC9imRYbvTyLZR7YIPrAG7uLhcX2CLoOaRfcoal1Vqv0pJYztAPgYNDtogtLGtS4nwy3bxXYYEiaHdwGb3MYhhL1Cpqceb_TrK7qKx77HanWx_PkBfbshmWj7H4Qn5AyW02kE1yLWoWAwWN_7aCvnkLbzalAD2NQWXeyP7pTWroLBcD8pO0zY4CBlsjWpkowsjWTbEE94KwDRqeytq2mDg5QcjnnIg71JkjbhwnDH3_T8PbUbrf7rg2kRsICpk3VWabrlGyy_uMr2UadCcOJ071_fXuZwPDiEfGoW6ULboYH5SzpKuxbXLLBCMBTRZJ7Usg",
        "refresh_token": "def50200fe7589a6f46245350858d0f8d520840b38d9c12186616f99a00b60b30cd226351066ee2580b34a0862fe99e660615c98be83f9a168d6be1a14a33aea9046af0245151b88c59dabf52d4bd6a5caeaefbd259abd363dc3cfe8b73a2b05dc133a82a799e5c91cc477a2274e89b3dd91840fe34de6928d22641ee64b685f021b99999ffdd3bf06384db87b64b4bfb84d7c38e75d2f389adf2d63fa7a4ee64669e9e686beca715b0dd279bbf366158123d416bc4fbd3c91e95b132550b5819b7aa25f6cfbe8b41de5bd386216414f3d8a3d6ce00cfdcb29651608ef8238bbc18335d433f4cfa16faf00794f67899b152fbe11182370e296cd6d07524c591428e27545f3b0da9baa04256055752ff9a4ffcdf3248e5765754cbf7faff49ad593210eb4613eefaa7e9462b848e53534c67da60334a0d9358ea86df9b9643984de4c975aed207d073673ec0e588b62ee7b36e61bc38ed07a6382c32cc779c43b279480b35e"
    },
    "user": {
        "name": "Mohamed Hamed",
        "email": "m.hamed2@nezam.io",
        "phone": "+201064931595",
        "updated_at": "2020-12-06T18:37:51.000000Z",
        "created_at": "2020-12-06T18:37:50.000000Z",
        "id": 12,
        "role": [
            "patient"
        ],
        "profile": {
            "user_id": 12,
            "firstname": "Mohamed",
            "lastname": "Hamed",
            "updated_at": "2020-12-06T18:37:50.000000Z",
            "created_at": "2020-12-06T18:37:50.000000Z",
            "id": 8
        }
    }
}
```
> Example response (422, Validation errors):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "firstname": [
            "The firstname field is required."
        ],
        "email": [
            "The email has already been taken."
        ],
        "phone": [
            "The phone has already been taken."
        ]
    }
}
```
<div id="execution-results-POSTapi-v1-auth-register" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-register"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-register"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-auth-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-register"></code></pre>
</div>
<form id="form-POSTapi-v1-auth-register" data-method="POST" data-path="api/v1/auth/register" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-register', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-register" onclick="tryItOut('POSTapi-v1-auth-register');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-register" onclick="cancelTryOut('POSTapi-v1-auth-register');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-register" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/auth/register</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="name" data-endpoint="POSTapi-v1-auth-register" data-component="body"  hidden>
<br>
Full name (required if firstname is absent).
</p>
<p>
<b><code>firstname</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="firstname" data-endpoint="POSTapi-v1-auth-register" data-component="body"  hidden>
<br>
First name (required if name is absent).
</p>
<p>
<b><code>lastname</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="lastname" data-endpoint="POSTapi-v1-auth-register" data-component="body"  hidden>
<br>
Last name (required if name is absent).
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-v1-auth-register" data-component="body"  hidden>
<br>
Email address.
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-auth-register" data-component="body" required  hidden>
<br>
Phone number with country code.
</p>
<p>
<b><code>firebase_jwt_token</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="firebase_jwt_token" data-endpoint="POSTapi-v1-auth-register" data-component="body"  hidden>
<br>
JWT token returned from firebase (optional), required to verify phone and use it for login.
</p>
<p>
<b><code>otp</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="otp" data-endpoint="POSTapi-v1-auth-register" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="password" data-endpoint="POSTapi-v1-auth-register" data-component="body" required  hidden>
<br>
Password.
</p>
<p>
<b><code>password_confirmation</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="password_confirmation" data-endpoint="POSTapi-v1-auth-register" data-component="body" required  hidden>
<br>
Password confirmation. Example 445566332255
</p>

</form>


## Login




> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/auth/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"username":"aliquam","password":"maiores"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "username": "aliquam",
    "password": "maiores"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "token": {
        "token_type": "Bearer",
        "expires_in": 31536000,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiYzMzNTc2ZDYxNzM0M2ZlYmU2OTZkYjE1ZWMzYTRhNjE0ZGJjNGI4ZDQ4MDFjNTU5NmIwYTIyY2UyNTE3Mzg0MDQwNGUxNzM5YmE5NTNhYjAiLCJpYXQiOjE2MDcyODAzNzksIm5iZiI6MTYwNzI4MDM3OSwiZXhwIjoxNjM4ODE2Mzc5LCJzdWIiOiI5Iiwic2NvcGVzIjpbIioiXX0.lhYW5Fjl6TE4jhG8eNG6CxXAEBfl_FeSeCCytqfCK4OEwn2RkVBZMqgRTgAzeayuL_4GeN9rtDC-o4q2Mdutx5m1VKPrV0Jl9X-ec9moH9POfUZPI8a9pj7bANYfrrRy7_htNuIHCXVpUGhVdTqCxWk_iDOJZgxzgf5kf2wfLFZWoqMoEc8-hnuFpxo8Y4WHqEe7-RWDQK2CyVXyKKEVjPAI-yjnQ8Cq0Fw3it_-_3y62Jrd-htGEkC2FUdrDPEjc7YEy8EuJHmY21tKZxypHcKiO6F72nxJJwJyukE6Hx390BaIFsy3uoMaHoPjoNF11xqSffAPKahHeueiLapYTUTqjbZBRFotZ5Lo-2hcne_kcW4SqS6qCdlv-yPQsah5K0TymOSl9_kSLcMS6RhXW6mzdXKbLzYme723yv7d3QCxqnwB0emcuL1r_quIipP7_KVKHLqN1lGtA-j5r1qlA-OJjn0O8xBCgcC57Gu_5w1e60F441T4GJjEPtri6K22gVTBntgXfb-9rQe2GYrJOGs3ydcBWB8AZq1m5ZI8gymoSIzib4qBlQFUrdjOEPQDmHmTPvpuZs1X0XnKJTEZWpv-T0mdFNrZtVN0N5_pNBu2rioASLEOlffPGNM1v8z4fspPkn1JbW9m6WGiNggSzGjgYTRQXkw7gYuwNlOuTm0",
        "refresh_token": "def5020040ea60cf608169383b65234f02e65e41406e5a7c57d3f733b59053f8f08ecc3470acec16f4a17819bf45c01eac21e82aba35d305ee0f1b7cb8a4b268bf6942425529b75fc8714159f2bbb9f34d8e6deb378caa6b579f2aace3f1966fd48d329a8dc0a68683ed2ac139d38240807fc2814fc4b2864412f357421cd694c6ddac842e44021ec02c164b198406ee6dddbeca7bb07a2d0ef334ef6c4171c65e36dbcb119726d0b6b7a89f5c53d4d6dff75b75aabe1ef860c1b62a41a3b8d0b9252792db63b36fc1952afc007a6684b0e9db5fd1575c2408343123a4bbc4737c39343775cd900d28da406037e0fc1d72636abe29490cda56771c1038cbb4dc3ff3a01dcd7c354835c9b81c9376a88fbcf3f825a457902ca946514b5b83b8e7586e90bd807e2708536fba69cd46768a14056ba89376e8c5fca0666b6622268afb2190ad5793d3ac6eaca23c21e683721ff06c3e6576b27ad036f6d79c8f9371b263a02d"
    },
    "user": {
        "id": 9,
        "name": "Hamedov",
        "email": "m.hamed@nezam.io",
        "phone": "+201064931597",
        "email_verified_at": null,
        "status": "Active",
        "created_at": "2020-10-30T18:16:10.000000Z",
        "updated_at": "2020-10-30T18:16:10.000000Z",
        "role": []
    }
}
```
> Example response (422, Validation errors):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "firstname": [
            "The firstname field is required."
        ],
        "email": [
            "The email has already been taken."
        ],
        "phone": [
            "The phone has already been taken."
        ]
    }
}
```
> Example response (401, Invalid username or password):

```json
{
    "message": "Invalid credentials"
}
```
> Example response (401, Phone not verified):

```json
{
    "message": "Your phone number is not verified. Please verify your phone number or login using email address.",
    "phone_verified": false
}
```
<div id="execution-results-POSTapi-v1-auth-login" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-login"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-login"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-auth-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-login"></code></pre>
</div>
<form id="form-POSTapi-v1-auth-login" data-method="POST" data-path="api/v1/auth/login" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-login', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-login" onclick="tryItOut('POSTapi-v1-auth-login');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-login" onclick="cancelTryOut('POSTapi-v1-auth-login');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-login" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/auth/login</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>username</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="username" data-endpoint="POSTapi-v1-auth-login" data-component="body" required  hidden>
<br>
Email address or phone number
</p>
<p>
<b><code>password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="password" data-endpoint="POSTapi-v1-auth-login" data-component="body" required  hidden>
<br>
Password
</p>

</form>


## Verify phone




> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/auth/verify" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"phone":"alias","firebase_jwt_token":"dolorum","otp":"sit"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/verify"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "phone": "alias",
    "firebase_jwt_token": "dolorum",
    "otp": "sit"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "token": {
        "token_type": "Bearer",
        "expires_in": 31536000,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiYzMzNTc2ZDYxNzM0M2ZlYmU2OTZkYjE1ZWMzYTRhNjE0ZGJjNGI4ZDQ4MDFjNTU5NmIwYTIyY2UyNTE3Mzg0MDQwNGUxNzM5YmE5NTNhYjAiLCJpYXQiOjE2MDcyODAzNzksIm5iZiI6MTYwNzI4MDM3OSwiZXhwIjoxNjM4ODE2Mzc5LCJzdWIiOiI5Iiwic2NvcGVzIjpbIioiXX0.lhYW5Fjl6TE4jhG8eNG6CxXAEBfl_FeSeCCytqfCK4OEwn2RkVBZMqgRTgAzeayuL_4GeN9rtDC-o4q2Mdutx5m1VKPrV0Jl9X-ec9moH9POfUZPI8a9pj7bANYfrrRy7_htNuIHCXVpUGhVdTqCxWk_iDOJZgxzgf5kf2wfLFZWoqMoEc8-hnuFpxo8Y4WHqEe7-RWDQK2CyVXyKKEVjPAI-yjnQ8Cq0Fw3it_-_3y62Jrd-htGEkC2FUdrDPEjc7YEy8EuJHmY21tKZxypHcKiO6F72nxJJwJyukE6Hx390BaIFsy3uoMaHoPjoNF11xqSffAPKahHeueiLapYTUTqjbZBRFotZ5Lo-2hcne_kcW4SqS6qCdlv-yPQsah5K0TymOSl9_kSLcMS6RhXW6mzdXKbLzYme723yv7d3QCxqnwB0emcuL1r_quIipP7_KVKHLqN1lGtA-j5r1qlA-OJjn0O8xBCgcC57Gu_5w1e60F441T4GJjEPtri6K22gVTBntgXfb-9rQe2GYrJOGs3ydcBWB8AZq1m5ZI8gymoSIzib4qBlQFUrdjOEPQDmHmTPvpuZs1X0XnKJTEZWpv-T0mdFNrZtVN0N5_pNBu2rioASLEOlffPGNM1v8z4fspPkn1JbW9m6WGiNggSzGjgYTRQXkw7gYuwNlOuTm0",
        "refresh_token": "def5020040ea60cf608169383b65234f02e65e41406e5a7c57d3f733b59053f8f08ecc3470acec16f4a17819bf45c01eac21e82aba35d305ee0f1b7cb8a4b268bf6942425529b75fc8714159f2bbb9f34d8e6deb378caa6b579f2aace3f1966fd48d329a8dc0a68683ed2ac139d38240807fc2814fc4b2864412f357421cd694c6ddac842e44021ec02c164b198406ee6dddbeca7bb07a2d0ef334ef6c4171c65e36dbcb119726d0b6b7a89f5c53d4d6dff75b75aabe1ef860c1b62a41a3b8d0b9252792db63b36fc1952afc007a6684b0e9db5fd1575c2408343123a4bbc4737c39343775cd900d28da406037e0fc1d72636abe29490cda56771c1038cbb4dc3ff3a01dcd7c354835c9b81c9376a88fbcf3f825a457902ca946514b5b83b8e7586e90bd807e2708536fba69cd46768a14056ba89376e8c5fca0666b6622268afb2190ad5793d3ac6eaca23c21e683721ff06c3e6576b27ad036f6d79c8f9371b263a02d"
    },
    "user": {
        "id": 9,
        "name": "Hamedov",
        "email": "m.hamed@nezam.io",
        "phone": "+201064931597",
        "email_verified_at": null,
        "status": "Active",
        "created_at": "2020-10-30T18:16:10.000000Z",
        "updated_at": "2020-10-30T18:16:10.000000Z",
        "role": []
    }
}
```
> Example response (422, Validation errors):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "firstname": [
            "The firstname field is required."
        ],
        "email": [
            "The email has already been taken."
        ],
        "phone": [
            "The phone has already been taken."
        ]
    }
}
```
> Example response (404, Phone number not found):

```json

{
    "message": "Not found message",
}
```
> Example response (401, Verification failed):

```json
{
    "message": "Invalid credentials"
}
```
<div id="execution-results-POSTapi-v1-auth-verify" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-verify"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-verify"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-auth-verify" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-verify"></code></pre>
</div>
<form id="form-POSTapi-v1-auth-verify" data-method="POST" data-path="api/v1/auth/verify" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-verify', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-verify" onclick="tryItOut('POSTapi-v1-auth-verify');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-verify" onclick="cancelTryOut('POSTapi-v1-auth-verify');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-verify" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/auth/verify</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-auth-verify" data-component="body" required  hidden>
<br>
Phone number
</p>
<p>
<b><code>firebase_jwt_token</code></b>&nbsp;&nbsp;<small>Firebase</small>     <i>optional</i> &nbsp;
<input type="text" name="firebase_jwt_token" data-endpoint="POSTapi-v1-auth-verify" data-component="body"  hidden>
<br>
jwt token (Required if otp is absent)
</p>
<p>
<b><code>otp</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="otp" data-endpoint="POSTapi-v1-auth-verify" data-component="body"  hidden>
<br>
Sinch otp (Required if firebase jwt token is absent)
</p>

</form>


## Refresh access token




> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/auth/refresh_token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"refresh_token":"molestiae"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/refresh_token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "refresh_token": "molestiae"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "token_type": "Bearer",
    "expires_in": 31536000,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiNTUxZDVkNzk1MjE3OWRmZDcxYzI3MmM5YTNlMDdmYzBkNTQ2MTBhMjFkOGU5MjIzYzgxOTU0YzIyMGY1OTAwOTkwNjBiNDNiZTA0OTBmMWEiLCJpYXQiOjE2MDcyODA1NDgsIm5iZiI6MTYwNzI4MDU0OCwiZXhwIjoxNjM4ODE2NTQ4LCJzdWIiOiI5Iiwic2NvcGVzIjpbIioiXX0.j9F5Y34t1uOHpnNkw8UwfvFumyQDhSf2vYJY4mLwWSfw-KeS7czQyBUiOQDK85JZUicVLGGG8kxwLkAUlJjq70ppJHhWkD9QOnpRxHJGU6BjIxDqlLGUXQpUhkjez51ji_TYhNSh4VSQ8Wb6jIi33AhwKCpSYy6R6MFZKB-y6wxXfnZNm3REUchFtDt-SkoFxBDEGR-ADwwRAw8T1KaVxXiy3d3EkcJnJbK0o9dwflMsC_CZW1vgplFfN3Q-2DdmvyvAQPu3OBTG98yCttzC_mkf1c2awB-q1QI4nmPtShPpDWqmgvvsEbZD7nLA9k067Bdjv9KhzvqFcssBvzWuActBWuBRSXkr6BLCzRbLFgFopDjVg556GH0bhNmq0gThMBdeE09HMfux5kq61y8H5nYdyoA9WJvaKNeTpJhyVGCEznYBlig4nmS7vpJheooOYTZEPgAA2ngo7AlKsJpwqkunExEi6-6efI_f_4NebW94TVoJvzacW4mfr_YL1yERcJtqgpzmjKIbuzs-BYpjo3UcjdvRDyPJ0Y_7N8cP1VgIJtKoC8KWvxtqEjCSRhcTpbpVgtB8jjdcH2JdnT7wAx2wTOJLAk-4DdtwkCQ4aX0dSykcN705741XBlgYM1Pdy65kiQ1mMiJ2EPWoBjEwQfteIjRMRKvM-380fbyfrCk",
    "refresh_token": "def5020006420444a23f173a91bbd9981ebfae547206e3122d4d02fdb75a338eca6616385886df66d7508a774e685ec3784930da042983d93755eedfbf70af73286c87a6fedb3d8200afdf5c3625106cd4c18e75486f1a6d2edb5bfb4cc5d7007371559cdf47a7ce7767ed8617f84d0aa658fc6d26e13ea11704c42bdf08da56770c10f2febaf2857074a810875636f9fd36e46de1d0896bddda0641766b3c3ac6c9ccce59ceae48305af3df5803198adc5a4eac58fa64b387948182d9f8cbfb0a7b6e738c2077d83720aa52f9a86e5a84d8553206a0a2b5eae11c181c9654c501ec6c2615080a82f39ca4facb527fcf1df2594c1bf76fbbf702e0307de5a854c174e701c12c8653be69a0d1115331c20b14b262bf39c2d0d8d3f618f2bb1f7bfde74ad93609370f9a4a00e7cdb86d3c4c54a71efaec967d606e52416aa655cb6e1f10870da0a1e05c6f5e1b4663a9862f24a5b76fc808fdadd15f2d87afb07445085bee"
}
```
> Example response (422, Validation errors):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "firstname": [
            "The firstname field is required."
        ],
        "email": [
            "The email has already been taken."
        ],
        "phone": [
            "The phone has already been taken."
        ]
    }
}
```
> Example response (401, Invalid refresh token):

```json
{
    "message": "Invalid credentials"
}
```
<div id="execution-results-POSTapi-v1-auth-refresh_token" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-refresh_token"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-refresh_token"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-auth-refresh_token" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-refresh_token"></code></pre>
</div>
<form id="form-POSTapi-v1-auth-refresh_token" data-method="POST" data-path="api/v1/auth/refresh_token" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-refresh_token', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-refresh_token" onclick="tryItOut('POSTapi-v1-auth-refresh_token');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-refresh_token" onclick="cancelTryOut('POSTapi-v1-auth-refresh_token');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-refresh_token" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/auth/refresh_token</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>refresh_token</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="refresh_token" data-endpoint="POSTapi-v1-auth-refresh_token" data-component="body" required  hidden>
<br>
Refersh token obtained during login
</p>

</form>


## Send otp




> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/auth/send-otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"phone":"atque"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/send-otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "phone": "atque"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTapi-v1-auth-send-otp" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-send-otp"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-send-otp"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-auth-send-otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-send-otp"></code></pre>
</div>
<form id="form-POSTapi-v1-auth-send-otp" data-method="POST" data-path="api/v1/auth/send-otp" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-send-otp', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-send-otp" onclick="tryItOut('POSTapi-v1-auth-send-otp');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-send-otp" onclick="cancelTryOut('POSTapi-v1-auth-send-otp');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-send-otp" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/auth/send-otp</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-auth-send-otp" data-component="body" required  hidden>
<br>
Phone number
</p>

</form>


## Check if phone or email exists.




> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/auth/check-phone" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"phone":"voluptatem"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/check-phone"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "phone": "voluptatem"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTapi-v1-auth-check-phone" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-check-phone"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-check-phone"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-auth-check-phone" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-check-phone"></code></pre>
</div>
<form id="form-POSTapi-v1-auth-check-phone" data-method="POST" data-path="api/v1/auth/check-phone" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-check-phone', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-check-phone" onclick="tryItOut('POSTapi-v1-auth-check-phone');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-check-phone" onclick="cancelTryOut('POSTapi-v1-auth-check-phone');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-check-phone" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/auth/check-phone</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-auth-check-phone" data-component="body" required  hidden>
<br>
Phone number
</p>

</form>


## Verify otp




> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/auth/verify-otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"phone":"inventore","otp":"tempora"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/verify-otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "phone": "inventore",
    "otp": "tempora"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTapi-v1-auth-verify-otp" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-auth-verify-otp"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-verify-otp"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-auth-verify-otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-verify-otp"></code></pre>
</div>
<form id="form-POSTapi-v1-auth-verify-otp" data-method="POST" data-path="api/v1/auth/verify-otp" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-verify-otp', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-auth-verify-otp" onclick="tryItOut('POSTapi-v1-auth-verify-otp');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-auth-verify-otp" onclick="cancelTryOut('POSTapi-v1-auth-verify-otp');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-auth-verify-otp" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/auth/verify-otp</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-auth-verify-otp" data-component="body" required  hidden>
<br>
Phone number
</p>
<p>
<b><code>otp</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="otp" data-endpoint="POSTapi-v1-auth-verify-otp" data-component="body" required  hidden>
<br>
Otp from sms
</p>

</form>


## Logout

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X DELETE \
    "http://guapa.com.sa/api/v1/auth/logout" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/logout"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json

[]
```
> Example response (401, Unauthorized):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-DELETEapi-v1-auth-logout" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-auth-logout"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-auth-logout"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-auth-logout"></code></pre>
</div>
<form id="form-DELETEapi-v1-auth-logout" data-method="DELETE" data-path="api/v1/auth/logout" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-auth-logout', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-auth-logout" onclick="tryItOut('DELETEapi-v1-auth-logout');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-auth-logout" onclick="cancelTryOut('DELETEapi-v1-auth-logout');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-auth-logout" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/auth/logout</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-auth-logout" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-auth-logout" data-component="header"></label>
</p>
</form>


## Get logged in user

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/auth/user" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/auth/user"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "id": 9,
    "name": "Hamedov",
    "email": "m.hamed@nezam.io",
    "phone": "+201064931597",
    "email_verified_at": null,
    "status": "Active",
    "created_at": "2020-10-30T18:16:10.000000Z",
    "updated_at": "2020-10-30T18:16:10.000000Z",
    "role": [
        "patient",
        "doctor",
        "manager"
    ],
    "profile": {
        "id": 5,
        "user_id": 9,
        "firstname": "Mohamed",
        "lastname": "Hamed",
        "gender": "Male",
        "birth_date": null,
        "about": "About me",
        "settings": null,
        "created_at": "2020-10-30T18:16:10.000000Z",
        "updated_at": "2020-10-30T18:16:10.000000Z",
        "photo": {
            "id": 10,
            "uuid": "a07ba900-1a20-4a3a-a25b-6afacfcc83f0",
            "name": "95440227_3064329763793493_6993060236609191936_n",
            "file_name": "95440227_3064329763793493_6993060236609191936_n.jpg",
            "mime_type": "image\/jpeg",
            "size": 10275,
            "order_column": 8,
            "created_at": "2020-10-30T18:16:10.000000Z",
            "updated_at": "2020-10-30T18:16:10.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/10\/95440227_3064329763793493_6993060236609191936_n.jpg",
            "large": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-small.jpg",
            "collection": "avatars"
        }
    }
}
```
> Example response (401, Unauthorized):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-GETapi-v1-auth-user" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-auth-user"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-user"></code></pre>
</div>
<div id="execution-error-GETapi-v1-auth-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-user"></code></pre>
</div>
<form id="form-GETapi-v1-auth-user" data-method="GET" data-path="api/v1/auth/user" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-user', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-auth-user" onclick="tryItOut('GETapi-v1-auth-user');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-auth-user" onclick="cancelTryOut('GETapi-v1-auth-user');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-auth-user" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/auth/user</code></b>
</p>
<p>
<label id="auth-GETapi-v1-auth-user" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-auth-user" data-component="header"></label>
</p>
</form>



