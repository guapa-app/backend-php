# User profile


## Get user by id

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/users/illo" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/users/illo"
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


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-GETapi-v1-users--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-users--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-users--id-"></code></pre>
</div>
<div id="execution-error-GETapi-v1-users--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-users--id-"></code></pre>
</div>
<form id="form-GETapi-v1-users--id-" data-method="GET" data-path="api/v1/users/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-users--id-" onclick="tryItOut('GETapi-v1-users--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-users--id-" onclick="cancelTryOut('GETapi-v1-users--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-users--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/users/{id}</code></b>
</p>
<p>
<label id="auth-GETapi-v1-users--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-users--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-v1-users--id-" data-component="url" required  hidden>
<br>
User id
</p>
</form>


## Update user profile

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/users/quibusdam" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"name":"nostrum","email":"vel","phone":"beatae","profile":{"firstname":"assumenda","lastname":"ipsam","gender":"excepturi","birth_date":"accusantium","about":"quidem","photo":"eos"},"password":"eaque","oldpassword":"ea","password_confirmation":"autem","reset_token":"omnis"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/users/quibusdam"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "name": "nostrum",
    "email": "vel",
    "phone": "beatae",
    "profile": {
        "firstname": "assumenda",
        "lastname": "ipsam",
        "gender": "excepturi",
        "birth_date": "accusantium",
        "about": "quidem",
        "photo": "eos"
    },
    "password": "eaque",
    "oldpassword": "ea",
    "password_confirmation": "autem",
    "reset_token": "omnis"
}

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-PUTapi-v1-users--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-users--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-users--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-users--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-users--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-users--id-" data-method="PUT" data-path="api/v1/users/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-users--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-users--id-" onclick="tryItOut('PUTapi-v1-users--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-users--id-" onclick="cancelTryOut('PUTapi-v1-users--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-users--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/users/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/users/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/users/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-users--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-users--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PUTapi-v1-users--id-" data-component="url" required  hidden>
<br>
User id
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="name" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Fullname 3 to 100 characters
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="email" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Email address
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="PUTapi-v1-users--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<details>
<summary>
<b><code>profile</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>profile.firstname</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="profile.firstname" data-endpoint="PUTapi-v1-users--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>profile.lastname</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="profile.lastname" data-endpoint="PUTapi-v1-users--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>profile.gender</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="profile.gender" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Gender Male, Female, Other
</p>
<p>
<b><code>profile.birth_date</code></b>&nbsp;&nbsp;<small>date</small>     <i>optional</i> &nbsp;
<input type="text" name="profile.birth_date" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Date of birth yyyy-mm-dd
</p>
<p>
<b><code>profile.about</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="profile.about" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Bio
</p>
<p>
<b><code>profile.photo</code></b>&nbsp;&nbsp;<small>image</small>     <i>optional</i> &nbsp;
<input type="text" name="profile.photo" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Profile picture
</p>
</details>
</p>
<p>
<b><code>password</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="password" name="password" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
New password required for change password
</p>
<p>
<details>
<summary>
<b><code>address</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>address.city_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="address.city_id" data-endpoint="PUTapi-v1-users--id-" data-component="body" required  hidden>
<br>
City id
</p>
<p>
<b><code>address.address_1</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address.address_1" data-endpoint="PUTapi-v1-users--id-" data-component="body" required  hidden>
<br>
Address line 1
</p>
<p>
<b><code>address.address_2</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address.address_2" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Address line 2
</p>
<p>
<b><code>address.postal_code</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address.postal_code" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Postal code
</p>
<p>
<b><code>address.lat</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address.lat" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Latitude
</p>
<p>
<b><code>address.lng</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address.lng" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Longitude
</p>
</details>
</p>
<p>
<b><code>oldpassword</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="password" name="oldpassword" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Old password required for change password
</p>
<p>
<b><code>password_confirmation</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="password" name="password_confirmation" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
Confirm new password
</p>
<p>
<b><code>reset_token</code></b>&nbsp;&nbsp;<small>Reset</small>     <i>optional</i> &nbsp;
<input type="text" name="reset_token" data-endpoint="PUTapi-v1-users--id-" data-component="body"  hidden>
<br>
password token in case of password reset instead of oldpassword
</p>

</form>



