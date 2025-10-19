# Offers


## Create offer

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/offers" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"product_id":5,"discount":17,"title":"earum","description":"quasi","image":{},"starts_at":"est","expires_at":"qui"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/offers"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "product_id": 5,
    "discount": 17,
    "title": "earum",
    "description": "quasi",
    "image": {},
    "starts_at": "est",
    "expires_at": "qui"
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
    "product_id": "5",
    "discount": "60",
    "starts_at": "2021-01-01T00:00:00.000000Z",
    "expires_at": "2021-01-25T00:00:00.000000Z",
    "updated_at": "2020-12-31T16:41:54.000000Z",
    "created_at": "2020-12-31T16:41:54.000000Z",
    "id": 8,
    "discount_string": "60%",
    "status": "Incoming"
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
<div id="execution-results-POSTapi-v1-offers" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-offers"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-offers"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-offers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-offers"></code></pre>
</div>
<form id="form-POSTapi-v1-offers" data-method="POST" data-path="api/v1/offers" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-offers', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-offers" onclick="tryItOut('POSTapi-v1-offers');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-offers" onclick="cancelTryOut('POSTapi-v1-offers');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-offers" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/offers</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-offers" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-offers" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>product_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="product_id" data-endpoint="POSTapi-v1-offers" data-component="body" required  hidden>
<br>
Product id for this offer.
</p>
<p>
<b><code>discount</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="discount" data-endpoint="POSTapi-v1-offers" data-component="body" required  hidden>
<br>
Discount percentage for this offer from 1 to 99.
</p>
<p>
<b><code>title</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="title" data-endpoint="POSTapi-v1-offers" data-component="body"  hidden>
<br>
Offer title 191 characters max.
</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="description" data-endpoint="POSTapi-v1-offers" data-component="body"  hidden>
<br>
Offer description 2000 characters max.
</p>
<p>
<b><code>image</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="image" data-endpoint="POSTapi-v1-offers" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>starts_at</code></b>&nbsp;&nbsp;<small>date</small>     <i>optional</i> &nbsp;
<input type="text" name="starts_at" data-endpoint="POSTapi-v1-offers" data-component="body"  hidden>
<br>
The start date of the offer.
</p>
<p>
<b><code>expires_at</code></b>&nbsp;&nbsp;<small>date</small>     <i>optional</i> &nbsp;
<input type="text" name="expires_at" data-endpoint="POSTapi-v1-offers" data-component="body"  hidden>
<br>
The end date of the offer.
</p>

</form>


## Update offer

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/offers/et" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"product_id":18,"discount":15,"title":"dolorem","description":"exercitationem","image":{},"starts_at":"libero","expires_at":"sequi"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/offers/et"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "product_id": 18,
    "discount": 15,
    "title": "dolorem",
    "description": "exercitationem",
    "image": {},
    "starts_at": "libero",
    "expires_at": "sequi"
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
    "product_id": "5",
    "discount": "60",
    "starts_at": "2021-01-01T00:00:00.000000Z",
    "expires_at": "2021-01-25T00:00:00.000000Z",
    "updated_at": "2020-12-31T16:41:54.000000Z",
    "created_at": "2020-12-31T16:41:54.000000Z",
    "id": 8,
    "discount_string": "60%",
    "status": "Incoming"
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
<div id="execution-results-PUTapi-v1-offers--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-offers--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-offers--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-offers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-offers--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-offers--id-" data-method="PUT" data-path="api/v1/offers/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-offers--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-offers--id-" onclick="tryItOut('PUTapi-v1-offers--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-offers--id-" onclick="cancelTryOut('PUTapi-v1-offers--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-offers--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/offers/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/offers/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/offers/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-offers--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-offers--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PUTapi-v1-offers--id-" data-component="url" required  hidden>
<br>

</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>product_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="product_id" data-endpoint="PUTapi-v1-offers--id-" data-component="body" required  hidden>
<br>
Product id for this offer.
</p>
<p>
<b><code>discount</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="discount" data-endpoint="PUTapi-v1-offers--id-" data-component="body" required  hidden>
<br>
Discount percentage for this offer from 1 to 99.
</p>
<p>
<b><code>title</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="title" data-endpoint="PUTapi-v1-offers--id-" data-component="body"  hidden>
<br>
Offer title 191 characters max.
</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="description" data-endpoint="PUTapi-v1-offers--id-" data-component="body"  hidden>
<br>
Offer description 2000 characters max.
</p>
<p>
<b><code>image</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="image" data-endpoint="PUTapi-v1-offers--id-" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>starts_at</code></b>&nbsp;&nbsp;<small>date</small>     <i>optional</i> &nbsp;
<input type="text" name="starts_at" data-endpoint="PUTapi-v1-offers--id-" data-component="body"  hidden>
<br>
The start date of the offer.
</p>
<p>
<b><code>expires_at</code></b>&nbsp;&nbsp;<small>date</small>     <i>optional</i> &nbsp;
<input type="text" name="expires_at" data-endpoint="PUTapi-v1-offers--id-" data-component="body"  hidden>
<br>
The end date of the offer.
</p>

</form>


## Delete offer

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X DELETE \
    "http://guapa.com.sa/api/v1/offers/enim" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/offers/enim"
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
    "id": "8"
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
<div id="execution-results-DELETEapi-v1-offers--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-offers--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-offers--id-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-offers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-offers--id-"></code></pre>
</div>
<form id="form-DELETEapi-v1-offers--id-" data-method="DELETE" data-path="api/v1/offers/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-offers--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-offers--id-" onclick="tryItOut('DELETEapi-v1-offers--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-offers--id-" onclick="cancelTryOut('DELETEapi-v1-offers--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-offers--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/offers/{id}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-offers--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-offers--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="DELETEapi-v1-offers--id-" data-component="url" required  hidden>
<br>

</p>
</form>



