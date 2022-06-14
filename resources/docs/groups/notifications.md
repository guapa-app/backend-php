# Notifications


## Get user notifications

<small class="badge badge-darkred">requires authentication</small>

Notification types and corresponding data
new-product, new-service => product_id
new-offer => product_id

> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/notifications?page=2&perPage=15" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/notifications"
);

let params = {
    "page": "2",
    "perPage": "15",
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
            "id": "b0fcf403-8719-4880-9a8c-745e2a7450e3",
            "type": "App\\Notifications\\OfferNotification",
            "notifiable_type": "user",
            "notifiable_id": 9,
            "data": {
                "product_id": 13,
                "summary": "خصم 50% على  من Big Hospital",
                "type": "new-offer"
            },
            "read_at": null,
            "created_at": "2021-01-08T14:05:09.000000Z",
            "updated_at": "2021-01-08T14:05:09.000000Z",
            "summary": "خصم 50% على  من Big Hospital"
        },
        {
            "id": "4528137c-3e34-4e18-9a75-f77487d7f672",
            "type": "App\\Notifications\\ProductNotification",
            "notifiable_type": "user",
            "notifiable_id": 9,
            "data": {
                "product_id": 14,
                "summary": "تم إضافة إجراء جديد بواسطة Hospital 3",
                "type": "new-service"
            },
            "read_at": null,
            "created_at": "2021-01-08T14:02:30.000000Z",
            "updated_at": "2021-01-08T14:02:30.000000Z",
            "summary": "تم إضافة إجراء جديد بواسطة Hospital 3"
        },
        {
            "id": "c177a3e1-81d2-499e-88d5-e41a787a2306",
            "type": "App\\Notifications\\ProductNotification",
            "notifiable_type": "user",
            "notifiable_id": 9,
            "data": {
                "product_id": 13,
                "summary": "تم إضافة منتج جديد بواسطة Big Hospital",
                "type": "new-product"
            },
            "read_at": null,
            "created_at": "2021-01-08T13:59:53.000000Z",
            "updated_at": "2021-01-08T13:59:53.000000Z",
            "summary": "تم إضافة منتج جديد بواسطة Big Hospital"
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/notifications?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/notifications?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/notifications?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/notifications",
    "per_page": 15,
    "prev_page_url": null,
    "to": 3,
    "total": 3
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-GETapi-v1-notifications" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-notifications"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-notifications"></code></pre>
</div>
<div id="execution-error-GETapi-v1-notifications" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-notifications"></code></pre>
</div>
<form id="form-GETapi-v1-notifications" data-method="GET" data-path="api/v1/notifications" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-notifications', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-notifications" onclick="tryItOut('GETapi-v1-notifications');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-notifications" onclick="cancelTryOut('GETapi-v1-notifications');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-notifications" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/notifications</code></b>
</p>
<p>
<label id="auth-GETapi-v1-notifications" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-notifications" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-notifications" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-notifications" data-component="query"  hidden>
<br>
Results to fetch per page
</p>
</form>


## Get only unread notifications

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/notifications/unread?page=2&perPage=15" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/notifications/unread"
);

let params = {
    "page": "2",
    "perPage": "15",
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
            "id": "b0fcf403-8719-4880-9a8c-745e2a7450e3",
            "type": "App\\Notifications\\OfferNotification",
            "notifiable_type": "user",
            "notifiable_id": 9,
            "data": {
                "product_id": 13,
                "summary": "خصم 50% على  من Big Hospital",
                "type": "new-offer"
            },
            "read_at": null,
            "created_at": "2021-01-08T14:05:09.000000Z",
            "updated_at": "2021-01-08T14:05:09.000000Z",
            "summary": "خصم 50% على  من Big Hospital"
        },
        {
            "id": "4528137c-3e34-4e18-9a75-f77487d7f672",
            "type": "App\\Notifications\\ProductNotification",
            "notifiable_type": "user",
            "notifiable_id": 9,
            "data": {
                "product_id": 14,
                "summary": "تم إضافة إجراء جديد بواسطة Hospital 3",
                "type": "new-service"
            },
            "read_at": null,
            "created_at": "2021-01-08T14:02:30.000000Z",
            "updated_at": "2021-01-08T14:02:30.000000Z",
            "summary": "تم إضافة إجراء جديد بواسطة Hospital 3"
        },
        {
            "id": "c177a3e1-81d2-499e-88d5-e41a787a2306",
            "type": "App\\Notifications\\ProductNotification",
            "notifiable_type": "user",
            "notifiable_id": 9,
            "data": {
                "product_id": 13,
                "summary": "تم إضافة منتج جديد بواسطة Big Hospital",
                "type": "new-product"
            },
            "read_at": null,
            "created_at": "2021-01-08T13:59:53.000000Z",
            "updated_at": "2021-01-08T13:59:53.000000Z",
            "summary": "تم إضافة منتج جديد بواسطة Big Hospital"
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/notifications?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/notifications?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/notifications?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/notifications",
    "per_page": 15,
    "prev_page_url": null,
    "to": 3,
    "total": 3
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-GETapi-v1-notifications-unread" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-notifications-unread"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-notifications-unread"></code></pre>
</div>
<div id="execution-error-GETapi-v1-notifications-unread" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-notifications-unread"></code></pre>
</div>
<form id="form-GETapi-v1-notifications-unread" data-method="GET" data-path="api/v1/notifications/unread" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-notifications-unread', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-notifications-unread" onclick="tryItOut('GETapi-v1-notifications-unread');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-notifications-unread" onclick="cancelTryOut('GETapi-v1-notifications-unread');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-notifications-unread" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/notifications/unread</code></b>
</p>
<p>
<label id="auth-GETapi-v1-notifications-unread" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-notifications-unread" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-notifications-unread" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-notifications-unread" data-component="query"  hidden>
<br>
Results to fetch per page
</p>
</form>


## Get unread notifications count

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/notifications/unread_count" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/notifications/unread_count"
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
    "count": 2
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-GETapi-v1-notifications-unread_count" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-notifications-unread_count"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-notifications-unread_count"></code></pre>
</div>
<div id="execution-error-GETapi-v1-notifications-unread_count" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-notifications-unread_count"></code></pre>
</div>
<form id="form-GETapi-v1-notifications-unread_count" data-method="GET" data-path="api/v1/notifications/unread_count" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-notifications-unread_count', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-notifications-unread_count" onclick="tryItOut('GETapi-v1-notifications-unread_count');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-notifications-unread_count" onclick="cancelTryOut('GETapi-v1-notifications-unread_count');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-notifications-unread_count" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/notifications/unread_count</code></b>
</p>
<p>
<label id="auth-GETapi-v1-notifications-unread_count" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-notifications-unread_count" data-component="header"></label>
</p>
</form>


## Mark all as read

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PATCH \
    "http://guapa.com.sa/api/v1/notifications/mark_all_read" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/notifications/mark_all_read"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "PATCH",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "message": "Success"
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-PATCHapi-v1-notifications-mark_all_read" hidden>
    <blockquote>Received response<span id="execution-response-status-PATCHapi-v1-notifications-mark_all_read"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-notifications-mark_all_read"></code></pre>
</div>
<div id="execution-error-PATCHapi-v1-notifications-mark_all_read" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-notifications-mark_all_read"></code></pre>
</div>
<form id="form-PATCHapi-v1-notifications-mark_all_read" data-method="PATCH" data-path="api/v1/notifications/mark_all_read" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-notifications-mark_all_read', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PATCHapi-v1-notifications-mark_all_read" onclick="tryItOut('PATCHapi-v1-notifications-mark_all_read');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PATCHapi-v1-notifications-mark_all_read" onclick="cancelTryOut('PATCHapi-v1-notifications-mark_all_read');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PATCHapi-v1-notifications-mark_all_read" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/notifications/mark_all_read</code></b>
</p>
<p>
<label id="auth-PATCHapi-v1-notifications-mark_all_read" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PATCHapi-v1-notifications-mark_all_read" data-component="header"></label>
</p>
</form>


## Mark notification as read

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/notifications/et/mark_read" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/notifications/et/mark_read"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "PUT",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "message": "Success"
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-PUTapi-v1-notifications--id--mark_read" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-notifications--id--mark_read"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-notifications--id--mark_read"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-notifications--id--mark_read" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-notifications--id--mark_read"></code></pre>
</div>
<form id="form-PUTapi-v1-notifications--id--mark_read" data-method="PUT" data-path="api/v1/notifications/{id}/mark_read" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-notifications--id--mark_read', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-notifications--id--mark_read" onclick="tryItOut('PUTapi-v1-notifications--id--mark_read');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-notifications--id--mark_read" onclick="cancelTryOut('PUTapi-v1-notifications--id--mark_read');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-notifications--id--mark_read" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/notifications/{id}/mark_read</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-notifications--id--mark_read" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-notifications--id--mark_read" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PUTapi-v1-notifications--id--mark_read" data-component="url" required  hidden>
<br>
Notification id
</p>
</form>



