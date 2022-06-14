# Orders


## Orders listing

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/orders?vendor_id=1&status=consequatur&page=2&perPage=15" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/orders"
);

let params = {
    "vendor_id": "1",
    "status": "consequatur",
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
[
    {
        "id": 8,
        "user_id": 2,
        "vendor_id": 2,
        "address_id": 1,
        "total": "5000.00",
        "status": "Accepted",
        "note": null,
        "name": null,
        "phone": null,
        "created_at": "2021-03-28T20:50:56.000000Z",
        "updated_at": "2021-03-28T21:04:23.000000Z",
        "vendor": {
            "id": 2,
            "name": "Vendor 2",
            "email": "vendor2@cosmo.com",
            "phone": "+620235698725",
            "about": "Hello world",
            "status": "1",
            "verified": true,
            "deleted_at": null,
            "whatsapp": null,
            "twitter": null,
            "instagram": null,
            "created_at": "2021-03-28T20:47:59.000000Z",
            "updated_at": "2021-03-28T20:47:59.000000Z",
            "type": 2,
            "working_days": "alsdjflkajsdlfkjaf",
            "working_hours": "lkjasdlkfjlaskdjflkaj",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        }
    },
    {
        "id": 7,
        "user_id": 2,
        "vendor_id": 1,
        "address_id": 1,
        "total": "400.00",
        "status": "Pending",
        "note": null,
        "name": null,
        "phone": null,
        "created_at": "2021-03-28T20:50:56.000000Z",
        "updated_at": "2021-03-28T20:50:56.000000Z",
        "vendor": {
            "id": 1,
            "name": "Vendor 1",
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
            "updated_at": "2021-03-26T22:01:33.000000Z",
            "type": 1,
            "working_days": "Saturday to Thursday",
            "working_hours": "9am to 5pm",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        }
    },
    {
        "id": 6,
        "user_id": 2,
        "vendor_id": 2,
        "address_id": 1,
        "total": "5000.00",
        "status": "Pending",
        "note": null,
        "name": null,
        "phone": null,
        "created_at": "2021-03-28T20:49:50.000000Z",
        "updated_at": "2021-03-28T20:49:50.000000Z",
        "vendor": {
            "id": 2,
            "name": "Vendor 2",
            "email": "vendor2@cosmo.com",
            "phone": "+620235698725",
            "about": "Hello world",
            "status": "1",
            "verified": true,
            "deleted_at": null,
            "whatsapp": null,
            "twitter": null,
            "instagram": null,
            "created_at": "2021-03-28T20:47:59.000000Z",
            "updated_at": "2021-03-28T20:47:59.000000Z",
            "type": 2,
            "working_days": "alsdjflkajsdlfkjaf",
            "working_hours": "lkjasdlkfjlaskdjflkaj",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        }
    },
    {
        "id": 5,
        "user_id": 2,
        "vendor_id": 1,
        "address_id": 1,
        "total": "400.00",
        "status": "Pending",
        "note": null,
        "name": null,
        "phone": null,
        "created_at": "2021-03-28T20:49:50.000000Z",
        "updated_at": "2021-03-28T20:49:50.000000Z",
        "vendor": {
            "id": 1,
            "name": "Vendor 1",
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
            "updated_at": "2021-03-26T22:01:33.000000Z",
            "type": 1,
            "working_days": "Saturday to Thursday",
            "working_hours": "9am to 5pm",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        }
    },
    {
        "id": 4,
        "user_id": 2,
        "vendor_id": 1,
        "address_id": 1,
        "total": "400.00",
        "status": "Pending",
        "note": null,
        "name": null,
        "phone": null,
        "created_at": "2021-03-28T20:45:49.000000Z",
        "updated_at": "2021-03-28T20:45:49.000000Z",
        "vendor": {
            "id": 1,
            "name": "Vendor 1",
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
            "updated_at": "2021-03-26T22:01:33.000000Z",
            "type": 1,
            "working_days": "Saturday to Thursday",
            "working_hours": "9am to 5pm",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        }
    },
    {
        "id": 3,
        "user_id": 2,
        "vendor_id": 1,
        "address_id": 1,
        "total": "400.00",
        "status": "Accepted",
        "note": null,
        "name": null,
        "phone": null,
        "created_at": "2021-03-28T18:00:02.000000Z",
        "updated_at": "2021-03-28T18:00:46.000000Z",
        "vendor": {
            "id": 1,
            "name": "Vendor 1",
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
            "updated_at": "2021-03-26T22:01:33.000000Z",
            "type": 1,
            "working_days": "Saturday to Thursday",
            "working_hours": "9am to 5pm",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        }
    },
    {
        "id": 2,
        "user_id": 2,
        "vendor_id": 1,
        "address_id": 1,
        "total": "400.00",
        "status": "Canceled",
        "note": null,
        "name": null,
        "phone": null,
        "created_at": "2021-03-26T22:11:18.000000Z",
        "updated_at": "2021-03-28T18:03:34.000000Z",
        "vendor": {
            "id": 1,
            "name": "Vendor 1",
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
            "updated_at": "2021-03-26T22:01:33.000000Z",
            "type": 1,
            "working_days": "Saturday to Thursday",
            "working_hours": "9am to 5pm",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        }
    },
    {
        "id": 1,
        "user_id": 2,
        "vendor_id": 1,
        "address_id": 1,
        "total": "200.00",
        "status": "Rejected",
        "note": null,
        "name": null,
        "phone": null,
        "created_at": "2021-03-26T22:05:38.000000Z",
        "updated_at": "2021-03-28T18:03:45.000000Z",
        "vendor": {
            "id": 1,
            "name": "Vendor 1",
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
            "updated_at": "2021-03-26T22:01:33.000000Z",
            "type": 1,
            "working_days": "Saturday to Thursday",
            "working_hours": "9am to 5pm",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        }
    }
]
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
<div id="execution-results-GETapi-v1-orders" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-orders"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-orders"></code></pre>
</div>
<div id="execution-error-GETapi-v1-orders" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-orders"></code></pre>
</div>
<form id="form-GETapi-v1-orders" data-method="GET" data-path="api/v1/orders" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-orders', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-orders" onclick="tryItOut('GETapi-v1-orders');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-orders" onclick="cancelTryOut('GETapi-v1-orders');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-orders" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/orders</code></b>
</p>
<p>
<label id="auth-GETapi-v1-orders" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-orders" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="vendor_id" data-endpoint="GETapi-v1-orders" data-component="query"  hidden>
<br>
Get orders for specific vendor.
</p>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="status" data-endpoint="GETapi-v1-orders" data-component="query"  hidden>
<br>
string. `Accepted`, `Pending`, `Canceled`, `Rejected`
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-orders" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-orders" data-component="query"  hidden>
<br>
Results to fetch per page
</p>
</form>


## Get Order by id




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/orders/ut" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/orders/ut"
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
    "id": 16,
    "user_id": 2,
    "vendor_id": 2,
    "address_id": 1,
    "total": "5400.00",
    "status": "Pending",
    "note": null,
    "name": null,
    "phone": null,
    "created_at": "2021-04-20T22:04:35.000000Z",
    "updated_at": "2021-04-20T22:04:35.000000Z",
    "vendor": {
        "id": 2,
        "name": "Vendor 2",
        "email": "vendor2@cosmo.com",
        "phone": "+620235698725",
        "about": "Hello world",
        "status": "1",
        "verified": true,
        "deleted_at": null,
        "whatsapp": null,
        "twitter": null,
        "instagram": null,
        "created_at": "2021-03-28T20:47:59.000000Z",
        "updated_at": "2021-03-28T20:47:59.000000Z",
        "type": 2,
        "working_days": "alsdjflkajsdlfkjaf",
        "working_hours": "lkjasdlkfjlaskdjflkaj",
        "specialty_ids": [],
        "likes_count": 0,
        "is_liked": false,
        "views_count": 0,
        "shares_count": 0
    },
    "user": {
        "id": 2,
        "name": "Mohamed Hamed",
        "email": "m.hamed@nezam.io",
        "phone": "+201064931597",
        "email_verified_at": null,
        "status": "Active",
        "created_at": "2021-03-26T21:46:56.000000Z",
        "updated_at": "2021-03-26T21:46:56.000000Z",
        "phone_verified_at": null,
        "role": []
    },
    "address": {
        "id": 1,
        "addressable_type": "vendor",
        "addressable_id": 1,
        "title": null,
        "city_id": 1,
        "address_1": "Bany Amer, Abo Refaey",
        "address_2": null,
        "postal_code": "44783",
        "lat": "30.05526418",
        "lng": "31.25606057",
        "type": 1,
        "phone": null,
        "created_at": "2021-03-26T22:01:33.000000Z",
        "updated_at": "2021-03-26T22:01:33.000000Z"
    },
    "items": [
        {
            "id": 13,
            "order_id": 16,
            "product_id": 1,
            "amount": "200.00",
            "quantity": 2,
            "appointment": null,
            "created_at": "2021-04-20T22:04:35.000000Z",
            "updated_at": "2021-04-20T22:04:35.000000Z",
            "product": {
                "id": 1,
                "vendor_id": 2,
                "title": "Great product",
                "description": "lkjalsdkfj lakjdsfl jaf k",
                "price": "200.00",
                "status": "Published",
                "review": "Approved",
                "type": "product",
                "terms": null,
                "deleted_at": null,
                "created_at": "2021-03-26T22:02:36.000000Z",
                "updated_at": "2021-03-26T22:02:36.000000Z",
                "category_ids": [],
                "address_ids": [],
                "likes_count": 0,
                "is_liked": false
            }
        },
        {
            "id": 14,
            "order_id": 16,
            "product_id": 2,
            "amount": "5000.00",
            "quantity": 1,
            "appointment": "{\"id\":\"1\",\"date\":\"2021-04-26\",\"from_time\":\"08:00:00\",\"to_time\":\"10:00:00\"}",
            "created_at": "2021-04-20T22:04:35.000000Z",
            "updated_at": "2021-04-20T22:04:35.000000Z",
            "product": {
                "id": 2,
                "vendor_id": 2,
                "title": "Service 1",
                "description": "asdkfjl ajsdflk jaldjf asdflkj\nadflkjalsdj asldkj lkads",
                "price": "5000.00",
                "status": "Published",
                "review": "Approved",
                "type": "service",
                "terms": "asldfjl ajsdlf jalskdjf laksjdf",
                "deleted_at": null,
                "created_at": "2021-03-28T20:48:45.000000Z",
                "updated_at": "2021-03-28T20:48:45.000000Z",
                "category_ids": [],
                "address_ids": [],
                "likes_count": 0,
                "is_liked": false
            }
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
<div id="execution-results-GETapi-v1-orders--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-orders--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-orders--id-"></code></pre>
</div>
<div id="execution-error-GETapi-v1-orders--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-orders--id-"></code></pre>
</div>
<form id="form-GETapi-v1-orders--id-" data-method="GET" data-path="api/v1/orders/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-orders--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-orders--id-" onclick="tryItOut('GETapi-v1-orders--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-orders--id-" onclick="cancelTryOut('GETapi-v1-orders--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-orders--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/orders/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-v1-orders--id-" data-component="url" required  hidden>
<br>
Order id
</p>
</form>


## Create order

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/orders" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"products":[{"id":12,"quantity":6,"appointment":{"id":4,"date":"2021-12-31T05:24:27+0000"},"staff_user_id":6}],"address_id":7,"note":"numquam","name":"molestiae","phone":"dolorem"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/orders"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "products": [
        {
            "id": 12,
            "quantity": 6,
            "appointment": {
                "id": 4,
                "date": "2021-12-31T05:24:27+0000"
            },
            "staff_user_id": 6
        }
    ],
    "address_id": 7,
    "note": "numquam",
    "name": "molestiae",
    "phone": "dolorem"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
[
    {
        "address_id": "1",
        "user_id": 2,
        "vendor_id": 2,
        "total": 5400,
        "updated_at": "2021-04-20T22:04:35.000000Z",
        "created_at": "2021-04-20T22:04:35.000000Z",
        "id": 16,
        "items": [
            {
                "id": 13,
                "order_id": 16,
                "product_id": 1,
                "amount": "200.00",
                "quantity": 2,
                "appointment": null,
                "created_at": "2021-04-20T22:04:35.000000Z",
                "updated_at": "2021-04-20T22:04:35.000000Z"
            },
            {
                "id": 14,
                "order_id": 16,
                "product_id": 2,
                "amount": "5000.00",
                "quantity": 1,
                "appointment": "{\"id\":\"1\",\"date\":\"2021-04-26\",\"from_time\":\"08:00:00\",\"to_time\":\"10:00:00\"}",
                "created_at": "2021-04-20T22:04:35.000000Z",
                "updated_at": "2021-04-20T22:04:35.000000Z"
            }
        ],
        "address": {
            "id": 1,
            "addressable_type": "vendor",
            "addressable_id": 1,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05526418",
            "lng": "31.25606057",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-26T22:01:33.000000Z",
            "updated_at": "2021-03-26T22:01:33.000000Z"
        },
        "user": {
            "id": 2,
            "name": "Mohamed Hamed",
            "email": "m.hamed@nezam.io",
            "phone": "+201064931597",
            "email_verified_at": null,
            "status": "Active",
            "created_at": "2021-03-26T21:46:56.000000Z",
            "updated_at": "2021-03-26T21:46:56.000000Z",
            "phone_verified_at": null,
            "role": []
        },
        "vendor": {
            "id": 2,
            "name": "Vendor 2",
            "email": "vendor2@cosmo.com",
            "phone": "+620235698725",
            "about": "Hello world",
            "status": "1",
            "verified": true,
            "deleted_at": null,
            "whatsapp": null,
            "twitter": null,
            "instagram": null,
            "created_at": "2021-03-28T20:47:59.000000Z",
            "updated_at": "2021-03-28T20:47:59.000000Z",
            "type": 2,
            "working_days": "alsdjflkajsdlfkjaf",
            "working_hours": "lkjasdlkfjlaskdjflkaj",
            "specialty_ids": [],
            "likes_count": 0,
            "is_liked": false,
            "views_count": 0,
            "shares_count": 0
        }
    }
]
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
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-POSTapi-v1-orders" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-orders"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-orders"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-orders" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-orders"></code></pre>
</div>
<form id="form-POSTapi-v1-orders" data-method="POST" data-path="api/v1/orders" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-orders', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-orders" onclick="tryItOut('POSTapi-v1-orders');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-orders" onclick="cancelTryOut('POSTapi-v1-orders');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-orders" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/orders</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-orders" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-orders" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<details>
<summary>
<b><code>products</code></b>&nbsp;&nbsp;<small>object[]</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>products[].id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="products.0.id" data-endpoint="POSTapi-v1-orders" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>products[].quantity</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="products.0.quantity" data-endpoint="POSTapi-v1-orders" data-component="body" required  hidden>
<br>

</p>
<p>
<details>
<summary>
<b><code>products[].appointment</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>

</summary>
<br>
<p>
<b><code>products[].appointment.id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="products.0.appointment.id" data-endpoint="POSTapi-v1-orders" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>products[].appointment.date</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="products.0.appointment.date" data-endpoint="POSTapi-v1-orders" data-component="body" required  hidden>
<br>
The value must be a valid date.
</p>
</details>
</p>

<p>
<b><code>products[].staff_user_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="products.0.staff_user_id" data-endpoint="POSTapi-v1-orders" data-component="body" required  hidden>
<br>

</p>
</details>
</p>
<p>
<b><code>address_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="address_id" data-endpoint="POSTapi-v1-orders" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>note</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="note" data-endpoint="POSTapi-v1-orders" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="name" data-endpoint="POSTapi-v1-orders" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-orders" data-component="body" required  hidden>
<br>

</p>

</form>


## Update order

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/orders/et" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"status":"quas"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/orders/et"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "status": "quas"
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
    "id": 8,
    "user_id": 2,
    "vendor_id": 2,
    "address_id": 1,
    "total": "5000.00",
    "status": "Accepted",
    "note": null,
    "name": null,
    "phone": null,
    "created_at": "2021-03-28T20:50:56.000000Z",
    "updated_at": "2021-03-28T21:04:23.000000Z"
}
```
> Example response (404, Order not found):

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
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-PUTapi-v1-orders--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-orders--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-orders--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-orders--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-orders--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-orders--id-" data-method="PUT" data-path="api/v1/orders/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-orders--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-orders--id-" onclick="tryItOut('PUTapi-v1-orders--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-orders--id-" onclick="cancelTryOut('PUTapi-v1-orders--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-orders--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/orders/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/orders/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/orders/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-orders--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-orders--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PUTapi-v1-orders--id-" data-component="url" required  hidden>
<br>
Order id
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="status" data-endpoint="PUTapi-v1-orders--id-" data-component="body"  hidden>
<br>
required. One of `Accepted`, `Rejected`, `Canceled`
</p>

</form>



