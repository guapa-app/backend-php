# Vendors


## List vendors




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/vendors?user_id=3&specialty_ids[]=1&keyword=Hospital+xxx&lat=30.2563&lng=31.9891&distance=50&page=2&perPage=15" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/vendors"
);

let params = {
    "user_id": "3",
    "specialty_ids[]": "1",
    "keyword": "Hospital xxx",
    "lat": "30.2563",
    "lng": "31.9891",
    "distance": "50",
    "page": "2",
    "perPage": "15",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
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
        "id": 8,
        "name": "Mohamed Clinic",
        "email": "drm.hamedov@cosmo.com",
        "phone": "+201064931597",
        "about": null,
        "status": "1",
        "verified": true,
        "deleted_at": null,
        "facebook": null,
        "twitter": null,
        "instagram": null,
        "created_at": "2020-12-10T01:29:57.000000Z",
        "updated_at": "2020-12-23T01:07:15.000000Z",
        "type": 0,
        "users_count": 2,
        "specialty_ids": [],
        "logo": {
            "id": 34,
            "uuid": "61290cb2-5761-48dc-a16d-0035a7bcc3ad",
            "name": "69",
            "file_name": "69.jpg",
            "mime_type": "image\/jpeg",
            "size": 201597,
            "order_column": 27,
            "created_at": "2020-12-10T01:29:57.000000Z",
            "updated_at": "2020-12-10T01:29:59.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/34\/69.jpg",
            "large": "http:\/\/cosmo.test\/storage\/34\/conversions\/69-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/34\/conversions\/69-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/34\/conversions\/69-small.jpg",
            "collection": "logos"
        }
    },
    {
        "id": 5,
        "name": "Hospital 3",
        "email": "admin@hospital3.com",
        "phone": "98956456564",
        "about": "<p> asdfasdlkfj laskjdfl kjasdlkfj<\/p>",
        "status": "1",
        "verified": true,
        "deleted_at": null,
        "facebook": null,
        "twitter": null,
        "instagram": null,
        "created_at": "2020-11-26T23:37:07.000000Z",
        "updated_at": "2020-11-26T23:37:07.000000Z",
        "type": 0,
        "users_count": 1,
        "specialty_ids": [],
        "logo": {
            "id": 27,
            "uuid": "1c69b8be-f2bf-4a7d-9024-1dd14d2dbd7c",
            "name": "4dc00350-8e53-4876-9140-44a14dbba6fe",
            "file_name": "4dc00350-8e53-4876-9140-44a14dbba6fe.jpg",
            "mime_type": "image\/jpeg",
            "size": 64545,
            "order_column": 21,
            "created_at": "2020-11-26T23:37:07.000000Z",
            "updated_at": "2020-11-26T23:37:09.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/27\/4dc00350-8e53-4876-9140-44a14dbba6fe.jpg",
            "large": "http:\/\/cosmo.test\/storage\/27\/conversions\/4dc00350-8e53-4876-9140-44a14dbba6fe-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/27\/conversions\/4dc00350-8e53-4876-9140-44a14dbba6fe-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/27\/conversions\/4dc00350-8e53-4876-9140-44a14dbba6fe-small.jpg",
            "collection": "logos"
        }
    },
    {
        "id": 4,
        "name": "Clinic 2",
        "email": "m.hamed@nezam.ios",
        "phone": "+5020002369",
        "about": "<p>asdf sadf asdfasdf sadf<\/p>",
        "status": "1",
        "verified": true,
        "deleted_at": null,
        "facebook": null,
        "twitter": null,
        "instagram": null,
        "created_at": "2020-11-26T23:31:48.000000Z",
        "updated_at": "2020-11-26T23:31:48.000000Z",
        "type": 0,
        "users_count": 1,
        "specialty_ids": [],
        "logo": {
            "id": 26,
            "uuid": "9e093537-c7e7-478d-bbbe-1f20c580029b",
            "name": "13410895",
            "file_name": "13410895.jpg",
            "mime_type": "image\/jpeg",
            "size": 26702,
            "order_column": 20,
            "created_at": "2020-11-26T23:31:48.000000Z",
            "updated_at": "2020-11-26T23:31:49.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/26\/13410895.jpg",
            "large": "http:\/\/cosmo.test\/storage\/26\/conversions\/13410895-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/26\/conversions\/13410895-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/26\/conversions\/13410895-small.jpg",
            "collection": "logos"
        }
    },
    {
        "id": 3,
        "name": "Clinic 1",
        "email": "midking2013@gmail.com",
        "phone": "+201064931599",
        "about": "<p>Hello world Hello<\/p>",
        "status": "1",
        "verified": true,
        "deleted_at": null,
        "facebook": null,
        "twitter": null,
        "instagram": null,
        "created_at": "2020-11-26T23:27:07.000000Z",
        "updated_at": "2020-11-26T23:27:07.000000Z",
        "type": 0,
        "users_count": 1,
        "specialty_ids": [],
        "logo": {
            "id": 25,
            "uuid": "89a70d24-68c0-4406-bb87-cc363d8fab2c",
            "name": "5c29479e6ed37-bpthumb",
            "file_name": "5c29479e6ed37-bpthumb.png",
            "mime_type": "image\/png",
            "size": 30815,
            "order_column": 19,
            "created_at": "2020-11-26T23:27:08.000000Z",
            "updated_at": "2020-11-26T23:27:09.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/25\/5c29479e6ed37-bpthumb.png",
            "large": "http:\/\/cosmo.test\/storage\/25\/conversions\/5c29479e6ed37-bpthumb-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/25\/conversions\/5c29479e6ed37-bpthumb-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/25\/conversions\/5c29479e6ed37-bpthumb-small.jpg",
            "collection": "logos"
        }
    },
    {
        "id": 2,
        "name": "New clinic",
        "email": "clinic1@cosmo.com",
        "phone": "+20123655445",
        "about": "<p>asdf asjdlfkj lsakjdflk jasdlkfjlaksjdfasdf sadf sadf<\/p>",
        "status": "1",
        "verified": true,
        "deleted_at": null,
        "facebook": null,
        "twitter": null,
        "instagram": null,
        "created_at": "2020-11-13T15:38:01.000000Z",
        "updated_at": "2020-11-13T15:39:56.000000Z",
        "type": 0,
        "users_count": 0,
        "specialty_ids": [],
        "logo": {
            "id": 12,
            "uuid": "f00650f1-1495-4007-8f78-5da4634487ef",
            "name": "13410895",
            "file_name": "13410895.jpg",
            "mime_type": "image\/jpeg",
            "size": 26702,
            "order_column": 10,
            "created_at": "2020-11-13T15:38:01.000000Z",
            "updated_at": "2020-11-13T15:38:02.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/12\/13410895.jpg",
            "large": "http:\/\/cosmo.test\/storage\/12\/conversions\/13410895-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/12\/conversions\/13410895-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/12\/conversions\/13410895-small.jpg",
            "collection": "logos"
        }
    },
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
        "updated_at": "2020-12-21T03:23:17.000000Z",
        "type": 1,
        "users_count": 1,
        "specialty_ids": [],
        "logo": {
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
]
```
> Example response (200, Location search response):

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
            "whatsapp": "+2010569856",
            "twitter": "https:\/\/www.twitter.com",
            "instagram": "https:\/\/www.instagram.com",
            "created_at": "2020-10-31T17:14:36.000000Z",
            "updated_at": "2021-01-01T15:07:52.000000Z",
            "type": 1,
            "working_days": null,
            "working_hours": null,
            "lat": "30.04788365",
            "lng": "31.26206872",
            "address_1": "Address line 1",
            "distance": 25.8,
            "users_count": 1,
            "products_count": 9,
            "offers_count": 1,
            "services_count": 0,
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0,
            "logo": {
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
        {
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
            "lat": "30.08095595",
            "lng": "31.27562997",
            "address_1": "Address line 1",
            "distance": 28,
            "users_count": 1,
            "products_count": 9,
            "offers_count": 1,
            "services_count": 0,
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0,
            "logo": {
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
        {
            "id": 5,
            "name": "Hospital 3",
            "email": "admin@hospital3.com",
            "phone": "98956456564",
            "about": "<p> asdfasdlkfj laskjdfl kjasdlkfj<\/p>",
            "status": "1",
            "verified": false,
            "deleted_at": null,
            "whatsapp": null,
            "twitter": null,
            "instagram": null,
            "created_at": "2020-11-26T23:37:07.000000Z",
            "updated_at": "2021-01-29T13:16:10.000000Z",
            "type": 0,
            "working_days": null,
            "working_hours": null,
            "lat": "30.05797574",
            "lng": "31.36755464",
            "address_1": "asdfsadf sadfasdf",
            "distance": 36,
            "users_count": 1,
            "products_count": 0,
            "offers_count": 0,
            "services_count": 1,
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0,
            "logo": {
                "id": 27,
                "uuid": "1c69b8be-f2bf-4a7d-9024-1dd14d2dbd7c",
                "name": "4dc00350-8e53-4876-9140-44a14dbba6fe",
                "file_name": "4dc00350-8e53-4876-9140-44a14dbba6fe.jpg",
                "mime_type": "image\/jpeg",
                "size": 64545,
                "order_column": 21,
                "created_at": "2020-11-26T23:37:07.000000Z",
                "updated_at": "2020-11-26T23:37:09.000000Z",
                "url": "http:\/\/cosmo.test\/storage\/27\/4dc00350-8e53-4876-9140-44a14dbba6fe.jpg",
                "large": "http:\/\/cosmo.test\/storage\/27\/conversions\/4dc00350-8e53-4876-9140-44a14dbba6fe-large.jpg",
                "medium": "http:\/\/cosmo.test\/storage\/27\/conversions\/4dc00350-8e53-4876-9140-44a14dbba6fe-medium.jpg",
                "small": "http:\/\/cosmo.test\/storage\/27\/conversions\/4dc00350-8e53-4876-9140-44a14dbba6fe-small.jpg",
                "collection": "logos"
            }
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/vendors?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/vendors?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/vendors?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/vendors",
    "per_page": "10",
    "prev_page_url": null,
    "to": 3,
    "total": 3
}
```
<div id="execution-results-GETapi-v1-vendors" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-vendors"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-vendors"></code></pre>
</div>
<div id="execution-error-GETapi-v1-vendors" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-vendors"></code></pre>
</div>
<form id="form-GETapi-v1-vendors" data-method="GET" data-path="api/v1/vendors" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-vendors', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-vendors" onclick="tryItOut('GETapi-v1-vendors');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-vendors" onclick="cancelTryOut('GETapi-v1-vendors');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-vendors" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/vendors</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>user_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="user_id" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
Get user vendors.
</p>
<p>
<b><code>specialty_ids[]</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="specialty_ids.0" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
array Get vendors with specific specialties.
</p>
<p>
<b><code>specialty_ids[].*</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="specialty_ids.0.*" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
Get vendors with specific specialties.
</p>
<p>
<b><code>keyword</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="keyword" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
String to search vendors.
</p>
<p>
<b><code>lat</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="lat" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
Filter vendors by location lat and lng.
</p>
<p>
<b><code>lng</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="lng" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
Filter vendors by location lat and lng.
</p>
<p>
<b><code>distance</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="distance" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
maximum distance for location filter in Km.
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-vendors" data-component="query"  hidden>
<br>
Results to fetch per page
</p>
</form>


## Get vendor details




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/vendors/ut" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/vendors/ut"
);

let headers = {
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
    "id": 1,
    "name": "Vendor Updated",
    "email": "vendor1@cosmo.com",
    "phone": "+201569895665",
    "about": "asdklfjlakjsdflkjasfjk",
    "status": "1",
    "verified": true,
    "deleted_at": null,
    "whatsapp": null,
    "twitter": null,
    "instagram": null,
    "created_at": "2021-03-26T22:01:33.000000Z",
    "updated_at": "2021-04-18T21:35:53.000000Z",
    "type": 1,
    "working_days": "Saturday to Thursday",
    "working_hours": "9am to 5pm",
    "products_count": 1,
    "offers_count": 0,
    "services_count": 0,
    "specialty_ids": [
        1
    ],
    "likes_count": 0,
    "is_liked": false,
    "views_count": 0,
    "shares_count": 0,
    "logo": {
        "id": 2,
        "uuid": "6f97bec0-3a56-4f58-98ce-7909929f4470",
        "name": "download",
        "file_name": "download.jpeg",
        "mime_type": "image\/jpeg",
        "size": 4373,
        "order_column": 2,
        "created_at": "2021-03-26T22:01:33.000000Z",
        "updated_at": "2021-03-26T22:01:33.000000Z",
        "url": "http:\/\/cosmo.test\/storage\/2\/download.jpeg",
        "large": "http:\/\/cosmo.test\/storage\/2\/conversions\/download-large.jpg",
        "medium": "http:\/\/cosmo.test\/storage\/2\/conversions\/download-medium.jpg",
        "small": "http:\/\/cosmo.test\/storage\/2\/conversions\/download-small.jpg",
        "collection": "logos"
    },
    "staff": [
        {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": [],
            "pivot": {
                "vendor_id": 1,
                "user_id": 2,
                "role": "manager",
                "email": null,
                "created_at": "2021-03-26T22:01:33.000000Z",
                "updated_at": "2021-03-26T22:01:33.000000Z"
            }
        }
    ],
    "specialties": [
        {
            "id": 1,
            "title": {
                "ar": "sdfjk",
                "en": "Ear"
            },
            "slug": "ear",
            "description": null,
            "font_icon": "accessible",
            "type": "specialty",
            "parent_id": null,
            "created_at": "2021-03-26T21:59:10.000000Z",
            "updated_at": "2021-03-26T21:59:10.000000Z",
            "pivot": {
                "taxable_id": 1,
                "taxonomy_id": 1,
                "taxable_type": "vendor"
            }
        }
    ],
    "work_days": [
        {
            "id": 5,
            "vendor_id": 1,
            "day": 0
        },
        {
            "id": 6,
            "vendor_id": 1,
            "day": 1
        }
    ],
    "appointments": [
        {
            "id": 1,
            "vendor_id": 1,
            "from_time": "08:00:00",
            "to_time": "10:00:00"
        }
    ]
}
```
> Example response (404, Vendor not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-GETapi-v1-vendors--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-vendors--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-vendors--id-"></code></pre>
</div>
<div id="execution-error-GETapi-v1-vendors--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-vendors--id-"></code></pre>
</div>
<form id="form-GETapi-v1-vendors--id-" data-method="GET" data-path="api/v1/vendors/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-vendors--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-vendors--id-" onclick="tryItOut('GETapi-v1-vendors--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-vendors--id-" onclick="cancelTryOut('GETapi-v1-vendors--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-vendors--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/vendors/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-v1-vendors--id-" data-component="url" required  hidden>
<br>
Vendor id
</p>
</form>


## Register vendor

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/vendors" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -F "name=Best Clinic" \
    -F "email=manager@bestclinic.com" \
    -F "phone=+201023569856" \
    -F "about=The best clinic in town" \
    -F "specialty_ids[]=15" \
    -F "whatsapp=ad" \
    -F "twitter=ab" \
    -F "instagram=ducimus" \
    -F "snapchat=voluptatum" \
    -F "type=0" \
    -F "working_days=ad" \
    -F "working_hours=voluptas" \
    -F "work_days[]=16" \
    -F "appointments[][from_time]=05:24:27" \
    -F "appointments[][to_time]=05:24:27" \
    -F "address[city_id]=65" \
    -F "address[address_1]=XYZ Street" \
    -F "address[address_2]=6th floot, next to xyz restaurant" \
    -F "address[postal_code]=56986" \
    -F "address[lat]=65.236589" \
    -F "address[lng]=62.659898" \
    -F "address[type]=3" \
    -F "logo=@C:\Users\Saif\AppData\Local\Temp\phpBF70.tmp" 
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/vendors"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
    "Accept-Language": "en",
};

const body = new FormData();
body.append('name', 'Best Clinic');
body.append('email', 'manager@bestclinic.com');
body.append('phone', '+201023569856');
body.append('about', 'The best clinic in town');
body.append('specialty_ids[]', '15');
body.append('whatsapp', 'ad');
body.append('twitter', 'ab');
body.append('instagram', 'ducimus');
body.append('snapchat', 'voluptatum');
body.append('type', '0');
body.append('working_days', 'ad');
body.append('working_hours', 'voluptas');
body.append('work_days[]', '16');
body.append('appointments[][from_time]', '05:24:27');
body.append('appointments[][to_time]', '05:24:27');
body.append('address[city_id]', '65');
body.append('address[address_1]', 'XYZ Street');
body.append('address[address_2]', '6th floot, next to xyz restaurant');
body.append('address[postal_code]', '56986');
body.append('address[lat]', '65.236589');
body.append('address[lng]', '62.659898');
body.append('address[type]', '3');
body.append('logo', document.querySelector('input[name="logo"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response => response.json());
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
<div id="execution-results-POSTapi-v1-vendors" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-vendors"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-vendors"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-vendors" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-vendors"></code></pre>
</div>
<form id="form-POSTapi-v1-vendors" data-method="POST" data-path="api/v1/vendors" data-authed="1" data-hasfiles="1" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"multipart\/form-data","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-vendors', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-vendors" onclick="tryItOut('POSTapi-v1-vendors');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-vendors" onclick="cancelTryOut('POSTapi-v1-vendors');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-vendors" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/vendors</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-vendors" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-vendors" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="name" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
Vendor name.
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="email" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
Vendor email.
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
Vendor phone.
</p>
<p>
<b><code>about</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="about" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Vendor about info.
</p>
<p>
<details>
<summary>
<b><code>specialty_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<br>
Vendor specializations.
</summary>
<br>
<p>
<b><code>specialty_ids[].*</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="specialty_ids.0.*" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Specialty id.
</p>
</details>
</p>
<p>
<b><code>logo</code></b>&nbsp;&nbsp;<small>file</small>     <i>optional</i> &nbsp;
<input type="file" name="logo" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Vendor logo.
</p>
<p>
<b><code>whatsapp</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="whatsapp" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Vendor whatsapp number.
</p>
<p>
<b><code>twitter</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="twitter" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Vendor twitter url.
</p>
<p>
<b><code>instagram</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="instagram" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Vendor instagram url.
</p>
<p>
<b><code>snapchat</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="snapchat" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Vendor snapchat url.
</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="type" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
Vendor type (0: hospital, 1: clinic, etc).
</p>
<p>
<b><code>working_days</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="working_days" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Working days.
</p>
<p>
<b><code>working_hours</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="working_hours" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Working hours.
</p>
<p>
<b><code>work_days</code></b>&nbsp;&nbsp;<small>integer[]</small>  &nbsp;
<input type="number" name="work_days.0" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<input type="number" name="work_days.1" data-endpoint="POSTapi-v1-vendors" data-component="body" hidden>
<br>

</p>
<p>
<details>
<summary>
<b><code>appointments</code></b>&nbsp;&nbsp;<small>object[]</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>appointments[].from_time</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="appointments.0.from_time" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
The value must be a valid date in the format H:i:s.
</p>
<p>
<b><code>appointments[].to_time</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="appointments.0.to_time" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
The value must be a valid date in the format H:i:s.
</p>
</details>
</p>
<p>
<details>
<summary>
<b><code>address</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>
Vendor address.
</summary>
<br>
<p>
<b><code>address.city_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="address.city_id" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
City id.
</p>
<p>
<b><code>address.address_1</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address.address_1" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
Address line 1.
</p>
<p>
<b><code>address.address_2</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address.address_2" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Address line 2.
</p>
<p>
<b><code>address.postal_code</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address.postal_code" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Postal code.
</p>
<p>
<b><code>address.lat</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="address.lat" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Latitude.
</p>
<p>
<b><code>address.lng</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="address.lng" data-endpoint="POSTapi-v1-vendors" data-component="body"  hidden>
<br>
Longitude.
</p>
<p>
<b><code>address.type</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="address.type" data-endpoint="POSTapi-v1-vendors" data-component="body" required  hidden>
<br>
Address type (see address types returned in api data).
</p>
</details>
</p>

</form>


## Update vendor

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/vendors/quaerat" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -F "name=Best Clinic" \
    -F "email=manager@bestclinic.com" \
    -F "phone=+201023569856" \
    -F "about=The best clinic in town" \
    -F "specialty_ids[]=5" \
    -F "whatsapp=harum" \
    -F "twitter=cupiditate" \
    -F "instagram=veniam" \
    -F "snapchat=laudantium" \
    -F "type=0" \
    -F "working_days=omnis" \
    -F "working_hours=eius" \
    -F "work_days[]=9" \
    -F "appointments[][from_time]=05:24:27" \
    -F "appointments[][to_time]=05:24:27" \
    -F "address[city_id]=65" \
    -F "address[address_1]=XYZ Street" \
    -F "address[address_2]=6th floot, next to xyz restaurant" \
    -F "address[postal_code]=56986" \
    -F "address[lat]=65.236589" \
    -F "address[lng]=62.659898" \
    -F "address[type]=3" \
    -F "logo=@C:\Users\Saif\AppData\Local\Temp\phpBF80.tmp" 
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/vendors/quaerat"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
    "Accept-Language": "en",
};

const body = new FormData();
body.append('name', 'Best Clinic');
body.append('email', 'manager@bestclinic.com');
body.append('phone', '+201023569856');
body.append('about', 'The best clinic in town');
body.append('specialty_ids[]', '5');
body.append('whatsapp', 'harum');
body.append('twitter', 'cupiditate');
body.append('instagram', 'veniam');
body.append('snapchat', 'laudantium');
body.append('type', '0');
body.append('working_days', 'omnis');
body.append('working_hours', 'eius');
body.append('work_days[]', '9');
body.append('appointments[][from_time]', '05:24:27');
body.append('appointments[][to_time]', '05:24:27');
body.append('address[city_id]', '65');
body.append('address[address_1]', 'XYZ Street');
body.append('address[address_2]', '6th floot, next to xyz restaurant');
body.append('address[postal_code]', '56986');
body.append('address[lat]', '65.236589');
body.append('address[lng]', '62.659898');
body.append('address[type]', '3');
body.append('logo', document.querySelector('input[name="logo"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "id": 8,
    "name": "Mohamed Clinic",
    "email": "drm.hamedov@cosmo.com",
    "phone": "+201064931597",
    "about": null,
    "status": "1",
    "verified": true,
    "deleted_at": null,
    "facebook": null,
    "twitter": null,
    "instagram": null,
    "created_at": "2020-12-10T01:29:57.000000Z",
    "updated_at": "2020-12-23T01:07:15.000000Z",
    "type": 0,
    "specialty_ids": [
        1
    ],
    "staff": [
        {
            "id": 9,
            "name": "Hamedov",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2020-10-30T18:16:10.000000Z",
            "updated_at": "2020-12-21T03:18:59.000000Z",
            "phone_verified_at": "2020-12-21 03:18:59",
            "role": [],
            "pivot": {
                "vendor_id": 8,
                "user_id": 9,
                "role": "manager",
                "email": "m.hamed@nezam.io",
                "created_at": "2020-12-10T01:29:57.000000Z",
                "updated_at": "2020-12-10T01:29:57.000000Z"
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
                "vendor_id": 8,
                "user_id": 16,
                "role": "doctor",
                "email": "hamedov@ccdrm.com",
                "created_at": "2020-12-10T01:29:57.000000Z",
                "updated_at": "2020-12-10T01:29:57.000000Z"
            }
        }
    ],
    "specialties": [
        {
            "id": 1,
            "title": {
                "ar": "تصنيف 1",
                "en": "Heart"
            },
            "slug": "heart",
            "description": {
                "ar": "<p>وصف<\/p>",
                "en": "<p>Description<\/p>"
            },
            "font_icon": "album",
            "type": "specialty",
            "parent_id": null,
            "created_at": "2020-11-25T20:14:14.000000Z",
            "updated_at": "2020-11-25T20:14:14.000000Z",
            "pivot": {
                "taxable_id": 8,
                "taxonomy_id": 1,
                "taxable_type": "vendor"
            }
        }
    ]
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
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-PUTapi-v1-vendors--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-vendors--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-vendors--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-vendors--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-vendors--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-vendors--id-" data-method="PUT" data-path="api/v1/vendors/{id}" data-authed="1" data-hasfiles="1" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"multipart\/form-data","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-vendors--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-vendors--id-" onclick="tryItOut('PUTapi-v1-vendors--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-vendors--id-" onclick="cancelTryOut('PUTapi-v1-vendors--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-vendors--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/vendors/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/vendors/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/vendors/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-vendors--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-vendors--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PUTapi-v1-vendors--id-" data-component="url" required  hidden>
<br>
Vendor id
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="name" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
Vendor name.
</p>
<p>
<b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="email" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
Vendor email.
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
Vendor phone.
</p>
<p>
<b><code>about</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="about" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Vendor about info.
</p>
<p>
<details>
<summary>
<b><code>specialty_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<br>
Vendor specializations.
</summary>
<br>
<p>
<b><code>specialty_ids[].*</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="specialty_ids.0.*" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Specialty id.
</p>
</details>
</p>
<p>
<b><code>logo</code></b>&nbsp;&nbsp;<small>file</small>     <i>optional</i> &nbsp;
<input type="file" name="logo" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Vendor logo.
</p>
<p>
<b><code>whatsapp</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="whatsapp" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Vendor whatsapp number.
</p>
<p>
<b><code>twitter</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="twitter" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Vendor twitter url.
</p>
<p>
<b><code>instagram</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="instagram" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Vendor instagram url.
</p>
<p>
<b><code>snapchat</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="snapchat" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Vendor snapchat url.
</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="type" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
Vendor type (0: hospital, 1: clinic, etc).
</p>
<p>
<b><code>working_days</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="working_days" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Working days.
</p>
<p>
<b><code>working_hours</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="working_hours" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Working hours.
</p>
<p>
<b><code>work_days</code></b>&nbsp;&nbsp;<small>integer[]</small>  &nbsp;
<input type="number" name="work_days.0" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<input type="number" name="work_days.1" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" hidden>
<br>

</p>
<p>
<details>
<summary>
<b><code>appointments</code></b>&nbsp;&nbsp;<small>object[]</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>appointments[].from_time</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="appointments.0.from_time" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
The value must be a valid date in the format H:i:s.
</p>
<p>
<b><code>appointments[].to_time</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="appointments.0.to_time" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
The value must be a valid date in the format H:i:s.
</p>
</details>
</p>
<p>
<details>
<summary>
<b><code>address</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>
Vendor address.
</summary>
<br>
<p>
<b><code>address.city_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="address.city_id" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
City id.
</p>
<p>
<b><code>address.address_1</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="address.address_1" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
Address line 1.
</p>
<p>
<b><code>address.address_2</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address.address_2" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Address line 2.
</p>
<p>
<b><code>address.postal_code</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="address.postal_code" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Postal code.
</p>
<p>
<b><code>address.lat</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="address.lat" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Latitude.
</p>
<p>
<b><code>address.lng</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="address.lng" data-endpoint="PUTapi-v1-vendors--id-" data-component="body"  hidden>
<br>
Longitude.
</p>
<p>
<b><code>address.type</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="address.type" data-endpoint="PUTapi-v1-vendors--id-" data-component="body" required  hidden>
<br>
Address type (see address types returned in api data).
</p>
</details>
</p>

</form>


## Share vendor

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/vendors/1/share" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/vendors/1/share"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "shares_count": 1
}
```
<div id="execution-results-POSTapi-v1-vendors--id--share" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-vendors--id--share"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-vendors--id--share"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-vendors--id--share" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-vendors--id--share"></code></pre>
</div>
<form id="form-POSTapi-v1-vendors--id--share" data-method="POST" data-path="api/v1/vendors/{id}/share" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-vendors--id--share', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-vendors--id--share" onclick="tryItOut('POSTapi-v1-vendors--id--share');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-vendors--id--share" onclick="cancelTryOut('POSTapi-v1-vendors--id--share');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-vendors--id--share" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/vendors/{id}/share</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-vendors--id--share" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-vendors--id--share" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-v1-vendors--id--share" data-component="url" required  hidden>
<br>
Vendor id.
</p>
</form>



