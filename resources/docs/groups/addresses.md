# Addresses


## Address list

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/addresses?addressable_id=3&addressable_type=vendor" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/addresses"
);

let params = {
    "addressable_id": "3",
    "addressable_type": "vendor",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

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
<div id="execution-results-GETapi-v1-addresses" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-addresses"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-addresses"></code></pre>
</div>
<div id="execution-error-GETapi-v1-addresses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-addresses"></code></pre>
</div>
<form id="form-GETapi-v1-addresses" data-method="GET" data-path="api/v1/addresses" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-addresses', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-addresses" onclick="tryItOut('GETapi-v1-addresses');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-addresses" onclick="cancelTryOut('GETapi-v1-addresses');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-addresses" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/addresses</code></b>
</p>
<p>
<label id="auth-GETapi-v1-addresses" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-addresses" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>addressable_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="addressable_id" data-endpoint="GETapi-v1-addresses" data-component="query" required  hidden>
<br>
Addressable entity id.
</p>
<p>
<b><code>addressable_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="addressable_type" data-endpoint="GETapi-v1-addresses" data-component="query" required  hidden>
<br>
Addressable entity type (vendor, user).
</p>
</form>


## Create Address

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/addresses" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"title":"quos","addressable_type":"user","addressable_id":10,"city_id":12,"address_1":"ut","address_2":"rerum","postal_code":"voluptas","lat":47.164301,"lng":7789.9,"type":1,"phone":"distinctio"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/addresses"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "title": "quos",
    "addressable_type": "user",
    "addressable_id": 10,
    "city_id": 12,
    "address_1": "ut",
    "address_2": "rerum",
    "postal_code": "voluptas",
    "lat": 47.164301,
    "lng": 7789.9,
    "type": 1,
    "phone": "distinctio"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
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
<div id="execution-results-POSTapi-v1-addresses" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-addresses"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-addresses"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-addresses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-addresses"></code></pre>
</div>
<form id="form-POSTapi-v1-addresses" data-method="POST" data-path="api/v1/addresses" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-addresses', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-addresses" onclick="tryItOut('POSTapi-v1-addresses');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-addresses" onclick="cancelTryOut('POSTapi-v1-addresses');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-addresses" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/addresses</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-addresses" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-addresses" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>title</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="title" data-endpoint="POSTapi-v1-addresses" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>addressable_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="addressable_type" data-endpoint="POSTapi-v1-addresses" data-component="body" required  hidden>
<br>
The value must be one of <code>vendor</code> or <code>user</code>.
</p>
<p>
<b><code>addressable_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="addressable_id" data-endpoint="POSTapi-v1-addresses" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>city_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="city_id" data-endpoint="POSTapi-v1-addresses" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>address_1</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address_1" data-endpoint="POSTapi-v1-addresses" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>address_2</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address_2" data-endpoint="POSTapi-v1-addresses" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>postal_code</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="postal_code" data-endpoint="POSTapi-v1-addresses" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>lat</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="lat" data-endpoint="POSTapi-v1-addresses" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>lng</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="lng" data-endpoint="POSTapi-v1-addresses" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="type" data-endpoint="POSTapi-v1-addresses" data-component="body" required  hidden>
<br>
The value must be one of <code>1</code>, <code>2</code>, <code>3</code>, <code>4</code>, <code>5</code>, <code>6</code>, or <code>7</code>.
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-addresses" data-component="body"  hidden>
<br>

</p>

</form>


## Update Address

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/addresses/3" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"title":"distinctio","addressable_type":"vendor","addressable_id":13,"city_id":15,"address_1":"ab","address_2":"mollitia","postal_code":"quis","lat":2568.40722673,"lng":2226.0576618,"type":5,"phone":"rerum"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/addresses/3"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "title": "distinctio",
    "addressable_type": "vendor",
    "addressable_id": 13,
    "city_id": 15,
    "address_1": "ab",
    "address_2": "mollitia",
    "postal_code": "quis",
    "lat": 2568.40722673,
    "lng": 2226.0576618,
    "type": 5,
    "phone": "rerum"
}

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
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
> Example response (404, Not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-PUTapi-v1-addresses--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-addresses--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-addresses--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-addresses--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-addresses--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-addresses--id-" data-method="PUT" data-path="api/v1/addresses/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-addresses--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-addresses--id-" onclick="tryItOut('PUTapi-v1-addresses--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-addresses--id-" onclick="cancelTryOut('PUTapi-v1-addresses--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-addresses--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/addresses/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/addresses/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/addresses/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-addresses--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-addresses--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="PUTapi-v1-addresses--id-" data-component="url" required  hidden>
<br>
Address id.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>title</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="title" data-endpoint="PUTapi-v1-addresses--id-" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>addressable_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="addressable_type" data-endpoint="PUTapi-v1-addresses--id-" data-component="body" required  hidden>
<br>
The value must be one of <code>vendor</code> or <code>user</code>.
</p>
<p>
<b><code>addressable_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="addressable_id" data-endpoint="PUTapi-v1-addresses--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>city_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="city_id" data-endpoint="PUTapi-v1-addresses--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>address_1</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address_1" data-endpoint="PUTapi-v1-addresses--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>address_2</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address_2" data-endpoint="PUTapi-v1-addresses--id-" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>postal_code</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="postal_code" data-endpoint="PUTapi-v1-addresses--id-" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>lat</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="lat" data-endpoint="PUTapi-v1-addresses--id-" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>lng</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="lng" data-endpoint="PUTapi-v1-addresses--id-" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="type" data-endpoint="PUTapi-v1-addresses--id-" data-component="body" required  hidden>
<br>
The value must be one of <code>1</code>, <code>2</code>, <code>3</code>, <code>4</code>, <code>5</code>, <code>6</code>, or <code>7</code>.
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="phone" data-endpoint="PUTapi-v1-addresses--id-" data-component="body"  hidden>
<br>

</p>

</form>


## Delete Address

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X DELETE \
    "http://guapa.com.sa/api/v1/addresses/cum" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/addresses/cum"
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


> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
> Example response (404, Not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-DELETEapi-v1-addresses--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-addresses--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-addresses--id-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-addresses--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-addresses--id-"></code></pre>
</div>
<form id="form-DELETEapi-v1-addresses--id-" data-method="DELETE" data-path="api/v1/addresses/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-addresses--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-addresses--id-" onclick="tryItOut('DELETEapi-v1-addresses--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-addresses--id-" onclick="cancelTryOut('DELETEapi-v1-addresses--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-addresses--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/addresses/{id}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-addresses--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-addresses--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="DELETEapi-v1-addresses--id-" data-component="url" required  hidden>
<br>

</p>
</form>



