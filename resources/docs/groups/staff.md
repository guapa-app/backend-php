# Staff


## Get vendor staff

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/staff" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/staff"
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
[
    {
        "id": 9,
        "name": "Hamedov",
        "email": "m.hamed@nezam.io",
        "phone": "+201064931597",
        "email_verified_at": null,
        "status": "Active",
        "created_at": "2020-10-30T18:16:10.000000Z",
        "updated_at": "2021-01-21T21:07:36.000000Z",
        "phone_verified_at": "2021-01-21 20:22:46",
        "role": [],
        "pivot": {
            "vendor_id": 3,
            "user_id": 9,
            "role": "manager",
            "email": "hamedovdov@clinic1.com",
            "created_at": "2020-11-26T23:27:08.000000Z",
            "updated_at": "2020-11-26T23:27:08.000000Z"
        }
    },
    {
        "id": 16,
        "name": "Mohamed Hamed",
        "email": "hamedov@ccdrm.com",
        "phone": "+203265986356",
        "email_verified_at": null,
        "status": "Active",
        "created_at": "2020-12-10T01:29:57.000000Z",
        "updated_at": "2020-12-10T01:29:57.000000Z",
        "phone_verified_at": null,
        "role": [],
        "pivot": {
            "vendor_id": 3,
            "user_id": 16,
            "role": "doctor",
            "email": null,
            "created_at": null,
            "updated_at": null
        }
    }
]
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
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
> Example response (404, Vendor not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-GETapi-v1-staff" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-staff"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-staff"></code></pre>
</div>
<div id="execution-error-GETapi-v1-staff" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-staff"></code></pre>
</div>
<form id="form-GETapi-v1-staff" data-method="GET" data-path="api/v1/staff" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-staff', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-staff" onclick="tryItOut('GETapi-v1-staff');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-staff" onclick="cancelTryOut('GETapi-v1-staff');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-staff" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/staff</code></b>
</p>
<p>
<label id="auth-GETapi-v1-staff" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-staff" data-component="header"></label>
</p>
</form>


## Create staff

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/staff" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"vendor_id":20,"name":"iusto","email":"voluptas","phone":"magni","role":"qui"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/staff"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "vendor_id": 20,
    "name": "iusto",
    "email": "voluptas",
    "phone": "magni",
    "role": "qui"
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
    "id": 3,
    "name": "Staff xx",
    "email": "vendor-1-1622313671@cosmo.com",
    "phone": "+5012659874555",
    "email_verified_at": null,
    "status": "Active",
    "created_at": "2021-05-29T18:41:11.000000Z",
    "updated_at": "2021-05-29T18:47:49.000000Z",
    "phone_verified_at": null,
    "role": [
        "patient",
        "manager"
    ]
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
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
> Example response (404, Vendor not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-POSTapi-v1-staff" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-staff"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-staff"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-staff" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-staff"></code></pre>
</div>
<form id="form-POSTapi-v1-staff" data-method="POST" data-path="api/v1/staff" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-staff', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-staff" onclick="tryItOut('POSTapi-v1-staff');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-staff" onclick="cancelTryOut('POSTapi-v1-staff');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-staff" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/staff</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-staff" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-staff" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="vendor_id" data-endpoint="POSTapi-v1-staff" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="name" data-endpoint="POSTapi-v1-staff" data-component="body" required  hidden>
<br>
Fullname 3 to 100 characters
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-v1-staff" data-component="body"  hidden>
<br>
Email address
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-staff" data-component="body" required  hidden>
<br>
Phone number
</p>
<p>
<b><code>role</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="role" data-endpoint="POSTapi-v1-staff" data-component="body" required  hidden>
<br>
One of manager, doctor
</p>

</form>


## Update staff

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/staff/quia" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"vendor_id":8,"name":"non","email":"voluptatem","phone":"voluptas","role":"voluptatibus"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/staff/quia"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "vendor_id": 8,
    "name": "non",
    "email": "voluptatem",
    "phone": "voluptas",
    "role": "voluptatibus"
}

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{
    "id": 3,
    "name": "Staff xx",
    "email": "vendor-1-1622313671@cosmo.com",
    "phone": "+5012659874555",
    "email_verified_at": null,
    "status": "Active",
    "created_at": "2021-05-29T18:41:11.000000Z",
    "updated_at": "2021-05-29T18:47:49.000000Z",
    "phone_verified_at": null,
    "role": [
        "patient",
        "manager"
    ]
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
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
> Example response (404, Vendor not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-PUTapi-v1-staff--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-staff--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-staff--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-staff--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-staff--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-staff--id-" data-method="PUT" data-path="api/v1/staff/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-staff--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-staff--id-" onclick="tryItOut('PUTapi-v1-staff--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-staff--id-" onclick="cancelTryOut('PUTapi-v1-staff--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-staff--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/staff/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/staff/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/staff/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-staff--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-staff--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PUTapi-v1-staff--id-" data-component="url" required  hidden>
<br>

</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="vendor_id" data-endpoint="PUTapi-v1-staff--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="name" data-endpoint="PUTapi-v1-staff--id-" data-component="body" required  hidden>
<br>
Fullname 3 to 100 characters
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="email" data-endpoint="PUTapi-v1-staff--id-" data-component="body"  hidden>
<br>
Email address
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="PUTapi-v1-staff--id-" data-component="body" required  hidden>
<br>
Phone number
</p>
<p>
<b><code>role</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="role" data-endpoint="PUTapi-v1-staff--id-" data-component="body" required  hidden>
<br>
One of manager, doctor
</p>

</form>


## Delete staff

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X DELETE \
    "http://guapa.com.sa/api/v1/staff/voluptatem/quia" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/staff/voluptatem/quia"
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
{
    "message": "Staff deleted successfully",
    "user_id": "16",
    "vendor_id": "3"
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
> Example response (404, Vendor not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-DELETEapi-v1-staff--userId---vendorId-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-staff--userId---vendorId-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-staff--userId---vendorId-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-staff--userId---vendorId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-staff--userId---vendorId-"></code></pre>
</div>
<form id="form-DELETEapi-v1-staff--userId---vendorId-" data-method="DELETE" data-path="api/v1/staff/{userId}/{vendorId}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-staff--userId---vendorId-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-staff--userId---vendorId-" onclick="tryItOut('DELETEapi-v1-staff--userId---vendorId-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-staff--userId---vendorId-" onclick="cancelTryOut('DELETEapi-v1-staff--userId---vendorId-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-staff--userId---vendorId-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/staff/{userId}/{vendorId}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-staff--userId---vendorId-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-staff--userId---vendorId-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>userId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="userId" data-endpoint="DELETEapi-v1-staff--userId---vendorId-" data-component="url" required  hidden>
<br>

</p>
<p>
<b><code>vendorId</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="vendorId" data-endpoint="DELETEapi-v1-staff--userId---vendorId-" data-component="url" required  hidden>
<br>

</p>
</form>



