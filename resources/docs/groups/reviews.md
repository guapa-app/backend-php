# Reviews


## List reviews

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/reviews?reviewable_type=product&reviewable_id=4&page=1&per_page=10" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"reviewable_type":"product","reviewable_id":17,"page":17,"per_page":11}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/reviews"
);

let params = {
    "reviewable_type": "product",
    "reviewable_id": "4",
    "page": "1",
    "per_page": "10",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "reviewable_type": "product",
    "reviewable_id": 17,
    "page": 17,
    "per_page": 11
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, Paginated reviews list):

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "reviewable_type": "vendor",
            "reviewable_id": 5,
            "user_id": 13,
            "stars": 4,
            "comment": "<p>Good hospital<\/p>",
            "created_at": "2020-12-09T17:31:05.000000Z",
            "updated_at": "2020-12-09T17:31:05.000000Z",
            "user": {
                "id": 13,
                "name": "Mohamed Docs",
                "email": "midking20135@gmail.com",
                "phone": "+5023659865",
                "email_verified_at": null,
                "status": "Active",
                "created_at": "2020-12-06T19:44:15.000000Z",
                "updated_at": "2020-12-06T19:44:15.000000Z",
                "phone_verified_at": null,
                "role": []
            }
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/reviews?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/reviews?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/reviews?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/reviews",
    "per_page": 15,
    "prev_page_url": null,
    "to": 1,
    "total": 1
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
> Example response (401, Unauthorized):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-GETapi-v1-reviews" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-reviews"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-reviews"></code></pre>
</div>
<div id="execution-error-GETapi-v1-reviews" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-reviews"></code></pre>
</div>
<form id="form-GETapi-v1-reviews" data-method="GET" data-path="api/v1/reviews" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reviews', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-reviews" onclick="tryItOut('GETapi-v1-reviews');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-reviews" onclick="cancelTryOut('GETapi-v1-reviews');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-reviews" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/reviews</code></b>
</p>
<p>
<label id="auth-GETapi-v1-reviews" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-reviews" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>reviewable_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="reviewable_type" data-endpoint="GETapi-v1-reviews" data-component="query" required  hidden>
<br>
Object type (vendor or product).
</p>
<p>
<b><code>reviewable_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="reviewable_id" data-endpoint="GETapi-v1-reviews" data-component="query" required  hidden>
<br>
Object id.
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-reviews" data-component="query"  hidden>
<br>
Page number for pagination.
</p>
<p>
<b><code>per_page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="per_page" data-endpoint="GETapi-v1-reviews" data-component="query"  hidden>
<br>
Records per page (5 to 30).
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>reviewable_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="reviewable_type" data-endpoint="GETapi-v1-reviews" data-component="body" required  hidden>
<br>
The value must be one of <code>vendor</code> or <code>product</code>.
</p>
<p>
<b><code>reviewable_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="reviewable_id" data-endpoint="GETapi-v1-reviews" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-reviews" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>per_page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="per_page" data-endpoint="GETapi-v1-reviews" data-component="body"  hidden>
<br>

</p>

</form>


## Create review

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/reviews" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"reviewable_type":"product","reviewable_id":4,"stars":5,"comment":"Very good product"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/reviews"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "reviewable_type": "product",
    "reviewable_id": 4,
    "stars": 5,
    "comment": "Very good product"
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
    "reviewable_type": "vendor",
    "reviewable_id": "1",
    "stars": "5",
    "comment": "Very good vendor",
    "user_id": 9,
    "updated_at": "2020-12-09T18:20:23.000000Z",
    "created_at": "2020-12-09T18:20:23.000000Z",
    "id": 3
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
> Example response (404, Reviewable entity not found):

```json

{
    "message": "Not found message",
}
```
> Example response (403, Already reviewed):

```json
{
    "message": "You have already reviewed this vendor"
}
```
> Example response (401, Unauthorized):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-POSTapi-v1-reviews" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-reviews"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-reviews"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-reviews" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-reviews"></code></pre>
</div>
<form id="form-POSTapi-v1-reviews" data-method="POST" data-path="api/v1/reviews" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-reviews', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-reviews" onclick="tryItOut('POSTapi-v1-reviews');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-reviews" onclick="cancelTryOut('POSTapi-v1-reviews');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-reviews" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/reviews</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-reviews" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-reviews" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>reviewable_type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="reviewable_type" data-endpoint="POSTapi-v1-reviews" data-component="body" required  hidden>
<br>
Object type (vendor or product).
</p>
<p>
<b><code>reviewable_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="reviewable_id" data-endpoint="POSTapi-v1-reviews" data-component="body" required  hidden>
<br>
Object id.
</p>
<p>
<b><code>stars</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="stars" data-endpoint="POSTapi-v1-reviews" data-component="body" required  hidden>
<br>
Number of stars.
</p>
<p>
<b><code>comment</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="comment" data-endpoint="POSTapi-v1-reviews" data-component="body"  hidden>
<br>
Review comment.
</p>

</form>



