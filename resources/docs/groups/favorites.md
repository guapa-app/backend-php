# Favorites


## Get favorites

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/favorites?type=product&page=2" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/favorites"
);

let params = {
    "type": "product",
    "page": "2",
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


> Example response (200, List favorite vendors):

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "name": "Big Hospital",
            "email": "big@hospital.com",
            "phone": "+201064931597",
            "about": "<p>About this vendor<\/p>",
            "status": "1",
            "verified": true,
            "deleted_at": null,
            "facebook": "https:\/\/www.facebook.com",
            "twitter": "https:\/\/www.twitter.com",
            "instagram": "https:\/\/www.instagram.com",
            "created_at": "2020-10-31T17:14:36.000000Z",
            "updated_at": "2020-11-28T00:34:27.000000Z",
            "specialty_ids": [],
            "pivot": {
                "user_id": 9,
                "favorable_id": 1,
                "favorable_type": "vendor"
            }
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/favorites?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/favorites?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/favorites?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/favorites",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```
> Example response (200, List favorite products):

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "vendor_id": 1,
            "title": "First product",
            "description": "<p>asdf asjdflk jaslkdjf asdf<\/p>",
            "price": "5000.00",
            "status": "Published",
            "review": "Approved",
            "type": "product",
            "terms": "<p>Product terms<\/p>",
            "deleted_at": null,
            "created_at": "2020-11-13T19:51:55.000000Z",
            "updated_at": "2020-11-26T13:43:57.000000Z",
            "category_ids": [],
            "address_ids": [],
            "pivot": {
                "user_id": 9,
                "favorable_id": 1,
                "favorable_type": "product"
            }
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/favorites?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/favorites?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/favorites?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/favorites",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```
> Example response (401, Unauthorized):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-GETapi-v1-favorites" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-favorites"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-favorites"></code></pre>
</div>
<div id="execution-error-GETapi-v1-favorites" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-favorites"></code></pre>
</div>
<form id="form-GETapi-v1-favorites" data-method="GET" data-path="api/v1/favorites" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-favorites', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-favorites" onclick="tryItOut('GETapi-v1-favorites');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-favorites" onclick="cancelTryOut('GETapi-v1-favorites');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-favorites" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/favorites</code></b>
</p>
<p>
<label id="auth-GETapi-v1-favorites" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-favorites" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="type" data-endpoint="GETapi-v1-favorites" data-component="query"  hidden>
<br>
Type of favorites to return (product, vendor, post).
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-favorites" data-component="query"  hidden>
<br>
Page number for pagination.
</p>
</form>


## Add entity to favorites

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/favorites" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"type":"product","id":4}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/favorites"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "type": "product",
    "id": 4
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, Add product to favorites):

```json
{
    "id": 1,
    "vendor_id": 1,
    "title": "First product",
    "description": "<p>asdf asjdflk jaslkdjf asdf<\/p>",
    "price": "5000.00",
    "status": "Published",
    "review": "Approved",
    "type": "product",
    "terms": "<p>Product terms<\/p>",
    "deleted_at": null,
    "created_at": "2020-11-13T19:51:55.000000Z",
    "updated_at": "2020-11-26T13:43:57.000000Z",
    "category_ids": [],
    "address_ids": []
}
```
> Example response (200, Add vendor to favorites):

```json
{
    "id": 1,
    "name": "Big Hospital",
    "email": "big@hospital.com",
    "phone": "+201064931597",
    "about": "<p>About this vendor<\/p>",
    "status": "1",
    "verified": true,
    "deleted_at": null,
    "facebook": "https:\/\/www.facebook.com",
    "twitter": "https:\/\/www.twitter.com",
    "instagram": "https:\/\/www.instagram.com",
    "created_at": "2020-10-31T17:14:36.000000Z",
    "updated_at": "2020-11-28T00:34:27.000000Z",
    "specialty_ids": []
}
```
> Example response (404, Invalid id/type):

```json

{
    "message": "Not found message",
}
```
> Example response (401, Unauthorized):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-POSTapi-v1-favorites" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-favorites"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-favorites"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-favorites" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-favorites"></code></pre>
</div>
<form id="form-POSTapi-v1-favorites" data-method="POST" data-path="api/v1/favorites" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-favorites', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-favorites" onclick="tryItOut('POSTapi-v1-favorites');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-favorites" onclick="cancelTryOut('POSTapi-v1-favorites');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-favorites" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/favorites</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-favorites" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-favorites" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="POSTapi-v1-favorites" data-component="body" required  hidden>
<br>
Object type (vendor, product or post).
</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-v1-favorites" data-component="body" required  hidden>
<br>
Object id.
</p>

</form>


## Delete Favorite

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X DELETE \
    "http://guapa.com.sa/api/v1/favorites/product/1" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/favorites/product/1"
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


> Example response (200, Remove entity from favorites):

```json
{
    "message": "Favorite deleted successfully",
    "id": "1",
    "type": "product"
}
```
> Example response (404, Invalid id/type):

```json

{
    "message": "Not found message",
}
```
> Example response (401, Unauthorized):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-DELETEapi-v1-favorites--type---id-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-favorites--type---id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-favorites--type---id-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-favorites--type---id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-favorites--type---id-"></code></pre>
</div>
<form id="form-DELETEapi-v1-favorites--type---id-" data-method="DELETE" data-path="api/v1/favorites/{type}/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-favorites--type---id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-favorites--type---id-" onclick="tryItOut('DELETEapi-v1-favorites--type---id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-favorites--type---id-" onclick="cancelTryOut('DELETEapi-v1-favorites--type---id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-favorites--type---id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/favorites/{type}/{id}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-favorites--type---id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-favorites--type---id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="DELETEapi-v1-favorites--type---id-" data-component="url" required  hidden>
<br>
int required
</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="DELETEapi-v1-favorites--type---id-" data-component="url" required  hidden>
<br>
int required
</p>
</form>



