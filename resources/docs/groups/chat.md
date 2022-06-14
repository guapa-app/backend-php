# Chat


## Get conversations

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/messaging/conversations?product_id=ut&vendor_id=aliquam&page=2&perPage=15" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/messaging/conversations"
);

let params = {
    "product_id": "ut",
    "vendor_id": "aliquam",
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
            "id": 1,
            "name": null,
            "product": {
                "id": 10,
                "vendor_id": 1,
                "title": "Good product",
                "description": "<p>ljafdljk alskdjf lkasjdflkjfsa<\/p>",
                "price": "2000.00",
                "status": "Published",
                "review": "Approved",
                "type": "product",
                "terms": "<p>Hello world<\/p>",
                "deleted_at": null,
                "created_at": "2021-01-08T13:40:54.000000Z",
                "updated_at": "2021-01-08T13:40:54.000000Z",
                "category_ids": [],
                "address_ids": [],
                "likes_count": 0,
                "is_liked": false
            },
            "other_party": {
                "id": 1,
                "type": "vendor",
                "participant_id": 2,
                "name": "Big Hospital",
                "photo": {
                    "id": 11,
                    "uuid": "dd0569b3-512f-4756-a159-a8baa971323f",
                    "name": "5c29479e6ed37-bpthumb",
                    "file_name": "5c29479e6ed37-bpthumb.png",
                    "mime_type": "image\/png",
                    "size": 30815,
                    "order_column": 9,
                    "created_at": "2020-10-31T18:01:30.000000Z",
                    "updated_at": "2020-10-31T18:01:31.000000Z",
                    "url": "http:\/\/cosmo.test\/storage\/11\/5c29479e6ed37-bpthumb.png",
                    "large": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-large.jpg",
                    "medium": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-medium.jpg",
                    "small": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-small.jpg",
                    "collection": "logos"
                }
            },
            "last_message": {
                "id": 7,
                "participant_id": 2,
                "message": "How much is this service?",
                "type": "text",
                "created_at": "2021-02-24T00:23:32.000000Z"
            },
            "has_new_messages": true
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/messaging\/conversations?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/messaging\/conversations?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/messaging\/conversations?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/messaging\/conversations",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```
> Example response (200, Get vendor conversations):

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "name": null,
            "product": {
                "id": 10,
                "vendor_id": 1,
                "title": "Good product",
                "description": "<p>ljafdljk alskdjf lkasjdflkjfsa<\/p>",
                "price": "2000.00",
                "status": "Published",
                "review": "Approved",
                "type": "product",
                "terms": "<p>Hello world<\/p>",
                "deleted_at": null,
                "created_at": "2021-01-08T13:40:54.000000Z",
                "updated_at": "2021-01-08T13:40:54.000000Z",
                "category_ids": [],
                "address_ids": [],
                "likes_count": 0,
                "is_liked": false
            },
            "other_party": {
                "id": 9,
                "type": "user",
                "participant_id": 1,
                "name": "Hamedov",
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
                    "laravel_through_key": 9,
                    "url": "http:\/\/cosmo.test\/storage\/10\/95440227_3064329763793493_6993060236609191936_n.jpg",
                    "large": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-large.jpg",
                    "medium": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-medium.jpg",
                    "small": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-small.jpg",
                    "collection": "avatars"
                }
            },
            "last_message": {
                "id": 7,
                "participant_id": 2,
                "message": "How much is this service?",
                "type": "text",
                "created_at": "2021-02-24T00:23:32.000000Z"
            },
            "has_new_messages": false
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/messaging\/conversations?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/messaging\/conversations?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/messaging\/conversations?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/messaging\/conversations",
    "per_page": 10,
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
> Example response (404, For vendor app when provided vendor_id is invalid):

```json

{
    "message": "Not found message",
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
<div id="execution-results-GETapi-v1-messaging-conversations" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-messaging-conversations"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-messaging-conversations"></code></pre>
</div>
<div id="execution-error-GETapi-v1-messaging-conversations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-messaging-conversations"></code></pre>
</div>
<form id="form-GETapi-v1-messaging-conversations" data-method="GET" data-path="api/v1/messaging/conversations" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-messaging-conversations', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-messaging-conversations" onclick="tryItOut('GETapi-v1-messaging-conversations');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-messaging-conversations" onclick="cancelTryOut('GETapi-v1-messaging-conversations');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-messaging-conversations" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/messaging/conversations</code></b>
</p>
<p>
<label id="auth-GETapi-v1-messaging-conversations" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-messaging-conversations" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>product_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="product_id" data-endpoint="GETapi-v1-messaging-conversations" data-component="query"  hidden>
<br>
Fetch Filter conversations by product.
</p>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="vendor_id" data-endpoint="GETapi-v1-messaging-conversations" data-component="query"  hidden>
<br>
Required to fetch conversations for vendor app.
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-messaging-conversations" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-messaging-conversations" data-component="query"  hidden>
<br>
Results to fetch per page
</p>
</form>


## Get conversation messages

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/messaging/messages?conversation_id=veritatis&vendor_id=ducimus&page=2&perPage=15" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/messaging/messages"
);

let params = {
    "conversation_id": "veritatis",
    "vendor_id": "ducimus",
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
            "id": 7,
            "conversation_id": 1,
            "participant_id": 2,
            "message": "How much is this service?",
            "read_by": "{}",
            "type": "text",
            "created_at": "2021-02-24T00:23:32.000000Z",
            "updated_at": "2021-02-24T00:23:32.000000Z",
            "participant": {
                "id": 2,
                "conversation_id": 1,
                "messageable_type": "vendor",
                "messageable_id": 1,
                "is_admin": "0",
                "status": "active",
                "last_read": "2021-02-23T22:24:07.000000Z",
                "created_at": "2021-02-23T22:24:07.000000Z",
                "updated_at": "2021-02-23T22:24:07.000000Z",
                "messageable": {
                    "id": 1,
                    "name": "Big Hospital",
                    "email": "big@hospital.com",
                    "phone": "+201064931597",
                    "about": "<p>About this vendor<\/p>",
                    "status": "1",
                    "verified": true,
                    "deleted_at": null,
                    "whatsapp": "+2010569856",
                    "twitter": "https:\/\/www.twitter.com",
                    "instagram": "https:\/\/www.instagram.com",
                    "created_at": "2020-10-31T17:14:36.000000Z",
                    "updated_at": "2021-01-01T15:07:52.000000Z",
                    "type": 1,
                    "working_days": null,
                    "working_hours": null,
                    "specialty_ids": [],
                    "likes_count": 0,
                    "is_liked": false,
                    "views_count": 0,
                    "shares_count": 0,
                    "photo": {
                        "id": 11,
                        "uuid": "dd0569b3-512f-4756-a159-a8baa971323f",
                        "name": "5c29479e6ed37-bpthumb",
                        "file_name": "5c29479e6ed37-bpthumb.png",
                        "mime_type": "image\/png",
                        "size": 30815,
                        "order_column": 9,
                        "created_at": "2020-10-31T18:01:30.000000Z",
                        "updated_at": "2020-10-31T18:01:31.000000Z",
                        "url": "http:\/\/cosmo.test\/storage\/11\/5c29479e6ed37-bpthumb.png",
                        "large": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-large.jpg",
                        "medium": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-medium.jpg",
                        "small": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-small.jpg",
                        "collection": "logos"
                    }
                }
            },
            "media": []
        },
        {
            "id": 1,
            "conversation_id": 1,
            "participant_id": 1,
            "message": "How much is this service?",
            "read_by": "{}",
            "type": "text",
            "created_at": "2021-02-23T22:42:19.000000Z",
            "updated_at": "2021-02-23T22:42:19.000000Z",
            "participant": {
                "id": 1,
                "conversation_id": 1,
                "messageable_type": "user",
                "messageable_id": 9,
                "is_admin": "1",
                "status": "active",
                "last_read": "2021-02-23T22:24:07.000000Z",
                "created_at": "2021-02-23T22:24:07.000000Z",
                "updated_at": "2021-02-23T22:24:07.000000Z",
                "messageable": {
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
                        "laravel_through_key": 9,
                        "url": "http:\/\/cosmo.test\/storage\/10\/95440227_3064329763793493_6993060236609191936_n.jpg",
                        "large": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-large.jpg",
                        "medium": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-medium.jpg",
                        "small": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-small.jpg",
                        "collection": "avatars"
                    }
                }
            },
            "media": []
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/messaging\/messages?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/messaging\/messages?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/messaging\/messages?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/messaging\/messages",
    "per_page": 15,
    "prev_page_url": null,
    "to": 2,
    "total": 2
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
> Example response (404, Conversation not found or for vendor app when provided vendor_id is invalid):

```json

{
    "message": "Not found message",
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
<div id="execution-results-GETapi-v1-messaging-messages" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-messaging-messages"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-messaging-messages"></code></pre>
</div>
<div id="execution-error-GETapi-v1-messaging-messages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-messaging-messages"></code></pre>
</div>
<form id="form-GETapi-v1-messaging-messages" data-method="GET" data-path="api/v1/messaging/messages" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-messaging-messages', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-messaging-messages" onclick="tryItOut('GETapi-v1-messaging-messages');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-messaging-messages" onclick="cancelTryOut('GETapi-v1-messaging-messages');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-messaging-messages" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/messaging/messages</code></b>
</p>
<p>
<label id="auth-GETapi-v1-messaging-messages" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-messaging-messages" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>conversation_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="conversation_id" data-endpoint="GETapi-v1-messaging-messages" data-component="query" required  hidden>
<br>
Conversation id to fetch messages for.
</p>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="vendor_id" data-endpoint="GETapi-v1-messaging-messages" data-component="query"  hidden>
<br>
Required to fetch messages for vendor app.
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-messaging-messages" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-messaging-messages" data-component="query"  hidden>
<br>
Results per page
</p>
</form>


## Mark conversation as read

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PATCH \
    "http://guapa.com.sa/api/v1/messaging/conversations/nemo/mark_as_read?vendor_id=modi" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/messaging/conversations/nemo/mark_as_read"
);

let params = {
    "vendor_id": "modi",
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
    method: "PATCH",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "id": 1,
    "name": null,
    "relatable_type": "product",
    "relatable_id": 10,
    "created_at": "2021-02-23T22:24:07.000000Z",
    "updated_at": "2021-02-23T22:24:07.000000Z",
    "pivot": {
        "messageable_id": 9,
        "conversation_id": 1,
        "messageable_type": "user"
    }
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
> Example response (404, Conversation not found or for vendor app when provided vendor_id is invalid):

```json

{
    "message": "Not found message",
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
<div id="execution-results-PATCHapi-v1-messaging-conversations--id--mark_as_read" hidden>
    <blockquote>Received response<span id="execution-response-status-PATCHapi-v1-messaging-conversations--id--mark_as_read"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-messaging-conversations--id--mark_as_read"></code></pre>
</div>
<div id="execution-error-PATCHapi-v1-messaging-conversations--id--mark_as_read" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-messaging-conversations--id--mark_as_read"></code></pre>
</div>
<form id="form-PATCHapi-v1-messaging-conversations--id--mark_as_read" data-method="PATCH" data-path="api/v1/messaging/conversations/{id}/mark_as_read" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-messaging-conversations--id--mark_as_read', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PATCHapi-v1-messaging-conversations--id--mark_as_read" onclick="tryItOut('PATCHapi-v1-messaging-conversations--id--mark_as_read');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PATCHapi-v1-messaging-conversations--id--mark_as_read" onclick="cancelTryOut('PATCHapi-v1-messaging-conversations--id--mark_as_read');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PATCHapi-v1-messaging-conversations--id--mark_as_read" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/messaging/conversations/{id}/mark_as_read</code></b>
</p>
<p>
<label id="auth-PATCHapi-v1-messaging-conversations--id--mark_as_read" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PATCHapi-v1-messaging-conversations--id--mark_as_read" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PATCHapi-v1-messaging-conversations--id--mark_as_read" data-component="url" required  hidden>
<br>

</p>
<p>
<b><code>conversation_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="conversation_id" data-endpoint="PATCHapi-v1-messaging-conversations--id--mark_as_read" data-component="url" required  hidden>
<br>
Conversation to mark as read.
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="vendor_id" data-endpoint="PATCHapi-v1-messaging-conversations--id--mark_as_read" data-component="query"  hidden>
<br>
Required for vendor app.
</p>
</form>


## Send message

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/messaging" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"product_id":12,"message":"aperiam","conversation_id":11,"vendor_id":9}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/messaging"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "product_id": 12,
    "message": "aperiam",
    "conversation_id": 11,
    "vendor_id": 9
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
    "conversation_id": 1,
    "message": "How much is this service?",
    "type": "text",
    "read_by": "{}",
    "participant_id": 1,
    "updated_at": "2021-02-24T00:19:06.000000Z",
    "created_at": "2021-02-24T00:19:06.000000Z",
    "id": 6,
    "participant": {
        "id": 1,
        "conversation_id": 1,
        "messageable_type": "user",
        "messageable_id": 9,
        "is_admin": "1",
        "status": "active",
        "last_read": "2021-02-23T22:24:07.000000Z",
        "created_at": "2021-02-23T22:24:07.000000Z",
        "updated_at": "2021-02-23T22:24:07.000000Z",
        "messageable": {
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
                "laravel_through_key": 9,
                "url": "http:\/\/cosmo.test\/storage\/10\/95440227_3064329763793493_6993060236609191936_n.jpg",
                "large": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-large.jpg",
                "medium": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-medium.jpg",
                "small": "http:\/\/cosmo.test\/storage\/10\/conversions\/95440227_3064329763793493_6993060236609191936_n-small.jpg",
                "collection": "avatars"
            }
        }
    },
    "media": []
}
```
> Example response (200, Send message as vendor):

```json
{
    "conversation_id": 1,
    "message": "How much is this service?",
    "type": "text",
    "read_by": "{}",
    "participant_id": 2,
    "updated_at": "2021-02-24T00:23:32.000000Z",
    "created_at": "2021-02-24T00:23:32.000000Z",
    "id": 7,
    "participant": {
        "id": 2,
        "conversation_id": 1,
        "messageable_type": "vendor",
        "messageable_id": 1,
        "is_admin": "0",
        "status": "active",
        "last_read": "2021-02-23T22:24:07.000000Z",
        "created_at": "2021-02-23T22:24:07.000000Z",
        "updated_at": "2021-02-23T22:24:07.000000Z",
        "messageable": {
            "id": 1,
            "name": "Big Hospital",
            "email": "big@hospital.com",
            "phone": "+201064931597",
            "about": "<p>About this vendor<\/p>",
            "status": "1",
            "verified": true,
            "deleted_at": null,
            "whatsapp": "+2010569856",
            "twitter": "https:\/\/www.twitter.com",
            "instagram": "https:\/\/www.instagram.com",
            "created_at": "2020-10-31T17:14:36.000000Z",
            "updated_at": "2021-01-01T15:07:52.000000Z",
            "type": 1,
            "working_days": null,
            "working_hours": null,
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0,
            "photo": {
                "id": 11,
                "uuid": "dd0569b3-512f-4756-a159-a8baa971323f",
                "name": "5c29479e6ed37-bpthumb",
                "file_name": "5c29479e6ed37-bpthumb.png",
                "mime_type": "image\/png",
                "size": 30815,
                "order_column": 9,
                "created_at": "2020-10-31T18:01:30.000000Z",
                "updated_at": "2020-10-31T18:01:31.000000Z",
                "url": "http:\/\/cosmo.test\/storage\/11\/5c29479e6ed37-bpthumb.png",
                "large": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-large.jpg",
                "medium": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-medium.jpg",
                "small": "http:\/\/cosmo.test\/storage\/11\/conversions\/5c29479e6ed37-bpthumb-small.jpg",
                "collection": "logos"
            }
        }
    },
    "media": []
}
```
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
> Example response (404, For vendor app when provided vendor_id is invalid):

```json

{
    "message": "Not found message",
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
<div id="execution-results-POSTapi-v1-messaging" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-messaging"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-messaging"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-messaging" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-messaging"></code></pre>
</div>
<form id="form-POSTapi-v1-messaging" data-method="POST" data-path="api/v1/messaging" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-messaging', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-messaging" onclick="tryItOut('POSTapi-v1-messaging');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-messaging" onclick="cancelTryOut('POSTapi-v1-messaging');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-messaging" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/messaging</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-messaging" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-messaging" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>product_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="product_id" data-endpoint="POSTapi-v1-messaging" data-component="body"  hidden>
<br>
Product id, required if `conversation_id` is absent
</p>
<p>
<b><code>message</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="message" data-endpoint="POSTapi-v1-messaging" data-component="body" required  hidden>
<br>
The message can be a string, image, array of images.
</p>
<p>
<b><code>conversation_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="conversation_id" data-endpoint="POSTapi-v1-messaging" data-component="body"  hidden>
<br>
Conversation id, required if `product_id` is absent
</p>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="vendor_id" data-endpoint="POSTapi-v1-messaging" data-component="body"  hidden>
<br>
Vendor id, required for vendor app.
</p>

</form>



