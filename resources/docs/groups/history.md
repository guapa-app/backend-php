# History


## History list

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/history?date=2021-01-01&page=1&perPage=10" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/history"
);

let params = {
    "date": "2021-01-01",
    "page": "1",
    "perPage": "10",
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


> Example response (200):

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 6,
            "user_id": 9,
            "details": "Hello world, I am sick",
            "record_date": null,
            "created_at": "2021-01-03T22:54:46.000000Z",
            "updated_at": "2021-01-03T22:54:46.000000Z",
            "image": {
                "id": 40,
                "uuid": "2ad19792-3ff5-463e-9402-f11f128d79f3",
                "name": "92171725_2646609305623764_4984241003624923136_o",
                "file_name": "92171725_2646609305623764_4984241003624923136_o.jpg",
                "mime_type": "image\/jpeg",
                "size": 201597,
                "order_column": 33,
                "created_at": "2021-01-03T22:54:46.000000Z",
                "updated_at": "2021-01-03T22:54:47.000000Z",
                "url": "http:\/\/cosmo.test\/storage\/40\/92171725_2646609305623764_4984241003624923136_o.jpg",
                "large": "http:\/\/cosmo.test\/storage\/40\/conversions\/92171725_2646609305623764_4984241003624923136_o-large.jpg",
                "medium": "http:\/\/cosmo.test\/storage\/40\/conversions\/92171725_2646609305623764_4984241003624923136_o-medium.jpg",
                "small": "http:\/\/cosmo.test\/storage\/40\/conversions\/92171725_2646609305623764_4984241003624923136_o-small.jpg",
                "collection": "history_images"
            }
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/history?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/history?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/history?page=1",
            "label": 1,
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http:\/\/cosmo.test\/api\/v1\/history",
    "per_page": "10",
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-GETapi-v1-history" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-history"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-history"></code></pre>
</div>
<div id="execution-error-GETapi-v1-history" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-history"></code></pre>
</div>
<form id="form-GETapi-v1-history" data-method="GET" data-path="api/v1/history" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-history', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-history" onclick="tryItOut('GETapi-v1-history');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-history" onclick="cancelTryOut('GETapi-v1-history');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-history" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/history</code></b>
</p>
<p>
<label id="auth-GETapi-v1-history" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-history" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>date</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="date" data-endpoint="GETapi-v1-history" data-component="query"  hidden>
<br>
History date.
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-history" data-component="query"  hidden>
<br>
Page number.
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-history" data-component="query"  hidden>
<br>
Records to fetch per page.
</p>
</form>


## History details

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/history/2" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/history/2"
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
    "id": 5,
    "user_id": 9,
    "details": "Caught a flu.",
    "record_date": "2020-12-25 00:00:00",
    "created_at": "2020-12-28T16:41:20.000000Z",
    "updated_at": "2020-12-28T16:41:20.000000Z"
}
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
<div id="execution-results-GETapi-v1-history--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-history--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-history--id-"></code></pre>
</div>
<div id="execution-error-GETapi-v1-history--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-history--id-"></code></pre>
</div>
<form id="form-GETapi-v1-history--id-" data-method="GET" data-path="api/v1/history/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-history--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-history--id-" onclick="tryItOut('GETapi-v1-history--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-history--id-" onclick="cancelTryOut('GETapi-v1-history--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-history--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/history/{id}</code></b>
</p>
<p>
<label id="auth-GETapi-v1-history--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-history--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-v1-history--id-" data-component="url" required  hidden>
<br>
History id.
</p>
</form>


## Create history

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/history" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"details":"Caught a flu","record_date":"2021-01-01","image":{}}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/history"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "details": "Caught a flu",
    "record_date": "2021-01-01",
    "image": {}
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
    "details": "Caught a flu.",
    "record_date": "2020-12-25",
    "user_id": 9,
    "updated_at": "2020-12-28T16:41:20.000000Z",
    "created_at": "2020-12-28T16:41:20.000000Z",
    "id": 5
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
<div id="execution-results-POSTapi-v1-history" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-history"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-history"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-history" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-history"></code></pre>
</div>
<form id="form-POSTapi-v1-history" data-method="POST" data-path="api/v1/history" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-history', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-history" onclick="tryItOut('POSTapi-v1-history');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-history" onclick="cancelTryOut('POSTapi-v1-history');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-history" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/history</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-history" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-history" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>details</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="details" data-endpoint="POSTapi-v1-history" data-component="body" required  hidden>
<br>
History details.
</p>
<p>
<b><code>record_date</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="record_date" data-endpoint="POSTapi-v1-history" data-component="body" required  hidden>
<br>
History date.
</p>
<p>
<b><code>image</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="image" data-endpoint="POSTapi-v1-history" data-component="body"  hidden>
<br>

</p>

</form>


## Update history

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/history/3" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"details":"Caught a flu","record_date":"2021-01-01","image":{}}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/history/3"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "details": "Caught a flu",
    "record_date": "2021-01-01",
    "image": {}
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
    "details": "Caught a flu.",
    "record_date": "2020-12-25",
    "user_id": 9,
    "updated_at": "2020-12-28T16:41:20.000000Z",
    "created_at": "2020-12-28T16:41:20.000000Z",
    "id": 5
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
> Example response (404, Not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-PUTapi-v1-history--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-history--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-history--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-history--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-history--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-history--id-" data-method="PUT" data-path="api/v1/history/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-history--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-history--id-" onclick="tryItOut('PUTapi-v1-history--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-history--id-" onclick="cancelTryOut('PUTapi-v1-history--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-history--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/history/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/history/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/history/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-history--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-history--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="PUTapi-v1-history--id-" data-component="url" required  hidden>
<br>
History id.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>details</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="details" data-endpoint="PUTapi-v1-history--id-" data-component="body" required  hidden>
<br>
History details.
</p>
<p>
<b><code>record_date</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="record_date" data-endpoint="PUTapi-v1-history--id-" data-component="body" required  hidden>
<br>
History date.
</p>
<p>
<b><code>image</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="image" data-endpoint="PUTapi-v1-history--id-" data-component="body"  hidden>
<br>

</p>

</form>


## Delete history

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X DELETE \
    "http://guapa.com.sa/api/v1/history/inventore" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/history/inventore"
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
[
    5
]
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
<div id="execution-results-DELETEapi-v1-history--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-history--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-history--id-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-history--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-history--id-"></code></pre>
</div>
<form id="form-DELETEapi-v1-history--id-" data-method="DELETE" data-path="api/v1/history/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-history--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-history--id-" onclick="tryItOut('DELETEapi-v1-history--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-history--id-" onclick="cancelTryOut('DELETEapi-v1-history--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-history--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/history/{id}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-history--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-history--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="DELETEapi-v1-history--id-" data-component="url" required  hidden>
<br>

</p>
</form>



