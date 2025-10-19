# Products


## Products listing




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/products?vendor_id=1&category_ids[]=20&type=product&list_type=default&keyword=Dell+laptop&min_price=200&max_price=5000&city_id=3&lat=30.5666&lng=31.3229&distance=20&page=2&perPage=15" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/products"
);

let params = {
    "vendor_id": "1",
    "category_ids[]": "20",
    "type": "product",
    "list_type": "default",
    "keyword": "Dell laptop",
    "min_price": "200",
    "max_price": "5000",
    "city_id": "3",
    "lat": "30.5666",
    "lng": "31.3229",
    "distance": "20",
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
        "id": 2,
        "vendor_id": 1,
        "title": "First product",
        "description": "<p>asdf asjdflk jaslkdjf asdf<\/p>",
        "price": "5000.00",
        "status": "Published",
        "review": "Approved",
        "type": "product",
        "terms": null,
        "deleted_at": null,
        "created_at": "2020-11-13T19:52:22.000000Z",
        "updated_at": "2020-11-13T19:52:22.000000Z",
        "category_ids": [],
        "address_ids": [],
        "vendor": {
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
            "specialty_ids": []
        },
        "image": {
            "id": 22,
            "uuid": "c4be2110-e805-4b95-907b-ec2f85e47e60",
            "name": "Ee8LPz4XsAA5QgA",
            "file_name": "Ee8LPz4XsAA5QgA.jpg",
            "mime_type": "image\/jpeg",
            "size": 91255,
            "order_column": 16,
            "created_at": "2020-11-17T23:16:01.000000Z",
            "updated_at": "2020-11-17T23:16:06.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/22\/Ee8LPz4XsAA5QgA.jpg",
            "large": "http:\/\/cosmo.test\/storage\/22\/conversions\/Ee8LPz4XsAA5QgA-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/22\/conversions\/Ee8LPz4XsAA5QgA-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/22\/conversions\/Ee8LPz4XsAA5QgA-small.jpg",
            "collection": "products"
        },
        "offer": null
    },
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
        "vendor": {
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
            "specialty_ids": []
        },
        "image": {
            "id": 19,
            "uuid": "13030a16-a72e-43b5-9fae-60ab54810b37",
            "name": "EfDtdLBXYAA2FOg",
            "file_name": "EfDtdLBXYAA2FOg.jpg",
            "mime_type": "image\/jpeg",
            "size": 27282,
            "order_column": 15,
            "created_at": "2020-11-17T22:19:54.000000Z",
            "updated_at": "2020-11-17T23:14:51.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/19\/EfDtdLBXYAA2FOg.jpg",
            "large": "http:\/\/cosmo.test\/storage\/19\/conversions\/EfDtdLBXYAA2FOg-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/19\/conversions\/EfDtdLBXYAA2FOg-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/19\/conversions\/EfDtdLBXYAA2FOg-small.jpg",
            "collection": "products"
        },
        "offer": {
            "id": 3,
            "product_id": 1,
            "discount": 19,
            "title": "Winter offer 1",
            "description": "<p>A great offer with 20% discount for 1 month of winter<\/p><p>asjdflkasjdflkj aslkdjf lkasjdfl kjaslkdfj<\/p><p>asdlkfjlaskjflk jasldkfj lkasjdflkjlasdfkj<\/p>",
            "starts_at": null,
            "expires_at": null,
            "created_at": "2020-11-20T21:18:59.000000Z",
            "updated_at": "2020-11-20T21:45:37.000000Z",
            "discount_string": "19%",
            "status": "Active"
        }
    }
]
```
<div id="execution-results-GETapi-v1-products" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-products"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-products"></code></pre>
</div>
<div id="execution-error-GETapi-v1-products" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-products"></code></pre>
</div>
<form id="form-GETapi-v1-products" data-method="GET" data-path="api/v1/products" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-products', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-products" onclick="tryItOut('GETapi-v1-products');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-products" onclick="cancelTryOut('GETapi-v1-products');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-products" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/products</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="vendor_id" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Get Products for specific user.
</p>
<p>
<b><code>category_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="category_ids.0" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<input type="number" name="category_ids.1" data-endpoint="GETapi-v1-products" data-component="query" hidden>
<br>
Filter products by categories.
</p>
<p>
<b><code>category_ids[].*</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="category_ids.0.*" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Category id.
</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="type" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Filter by type (product, service).
</p>
<p>
<b><code>list_type</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="list_type" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Filter by list type (default, most_viewed, most_ordered, offers).
</p>
<p>
<b><code>keyword</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="keyword" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
String to search Products.
</p>
<p>
<b><code>min_price</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="min_price" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Specify minimum price.
</p>
<p>
<b><code>max_price</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="max_price" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Specify maximum price.
</p>
<p>
<b><code>city_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="city_id" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
City id.
</p>
<p>
<b><code>lat</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="lat" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Latitude.
</p>
<p>
<b><code>lng</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="lng" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Longitude.
</p>
<p>
<b><code>distance</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="distance" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Distance in KM used along with lat and lng.
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-products" data-component="query"  hidden>
<br>
Results to fetch per page
</p>
</form>


## Get Product by id




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/products/sunt" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/products/sunt"
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
    "category_ids": [
        1
    ],
    "address_ids": [
        2
    ],
    "likes_count": 0,
    "is_liked": false,
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
        "description": "",
        "specialty_ids": [],
        "likes_count": 0,
        "is_liked": false,
        "views_count": 0,
        "shares_count": 0,
        "logo": {
            "id": 4,
            "uuid": "0419bf5f-5922-4a46-84a0-b0ae3031de3c",
            "name": "download",
            "file_name": "download.jpeg",
            "mime_type": "image\/jpeg",
            "size": 4373,
            "order_column": 4,
            "created_at": "2021-03-28T20:47:59.000000Z",
            "updated_at": "2021-03-28T20:47:59.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/4\/download.jpeg",
            "large": "http:\/\/cosmo.test\/storage\/4\/conversions\/download-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/4\/conversions\/download-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/4\/conversions\/download-small.jpg",
            "collection": "logos"
        },
        "appointments": [
            {
                "id": 1,
                "vendor_id": 2,
                "from_time": "08:00:00",
                "to_time": "10:00:00"
            }
        ],
        "work_days": [
            {
                "id": 5,
                "vendor_id": 2,
                "day": 0
            },
            {
                "id": 6,
                "vendor_id": 2,
                "day": 1
            }
        ]
    },
    "offer": null,
    "media": [
        {
            "id": 5,
            "uuid": "c00c3b6e-7912-4e57-a21c-8e83a5899613",
            "name": "download",
            "file_name": "download.jpeg",
            "mime_type": "image\/jpeg",
            "size": 4373,
            "order_column": 5,
            "created_at": "2021-03-28T20:48:45.000000Z",
            "updated_at": "2021-03-28T20:48:46.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/5\/download.jpeg",
            "large": "http:\/\/cosmo.test\/storage\/5\/conversions\/download-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/5\/conversions\/download-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/5\/conversions\/download-small.jpg",
            "collection": "products"
        }
    ],
    "categories": [
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
                "taxable_id": 2,
                "taxonomy_id": 1,
                "taxable_type": "product"
            }
        }
    ],
    "addresses": [
        {
            "id": 2,
            "addressable_type": "vendor",
            "addressable_id": 2,
            "title": null,
            "city_id": 1,
            "address_1": "Bany Amer, Abo Refaey",
            "address_2": null,
            "postal_code": "44783",
            "lat": "30.05853289",
            "lng": "31.30017755",
            "type": 1,
            "phone": null,
            "created_at": "2021-03-28T20:47:59.000000Z",
            "updated_at": "2021-03-28T20:47:59.000000Z",
            "pivot": {
                "product_id": 2,
                "address_id": 2
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
<div id="execution-results-GETapi-v1-products--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-products--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-products--id-"></code></pre>
</div>
<div id="execution-error-GETapi-v1-products--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-products--id-"></code></pre>
</div>
<form id="form-GETapi-v1-products--id-" data-method="GET" data-path="api/v1/products/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-products--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-products--id-" onclick="tryItOut('GETapi-v1-products--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-products--id-" onclick="cancelTryOut('GETapi-v1-products--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-products--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/products/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-v1-products--id-" data-component="url" required  hidden>
<br>
Product id
</p>
</form>


## Create product

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/products" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -F "vendor_id=4" \
    -F "title=deserunt" \
    -F "description=dolorem" \
    -F "price=reiciendis" \
    -F "status=sit" \
    -F "category_ids[]=19" \
    -F "address_ids[]=10" \
    -F "terms=ut" \
    -F "type=product" \
    -F "review=Pending" \
    -F "category_id=2" \
    -F "media[]=mollitia" \
    -F "keep_media=praesentium" \
    -F "media[]=@C:\Users\Saif\AppData\Local\Temp\phpBFA1.tmp" 
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/products"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
    "Accept-Language": "en",
};

const body = new FormData();
body.append('vendor_id', '4');
body.append('title', 'deserunt');
body.append('description', 'dolorem');
body.append('price', 'reiciendis');
body.append('status', 'sit');
body.append('category_ids[]', '19');
body.append('address_ids[]', '10');
body.append('terms', 'ut');
body.append('type', 'product');
body.append('review', 'Pending');
body.append('category_id', '2');
body.append('media[]', 'mollitia');
body.append('keep_media', 'praesentium');
body.append('media[]', document.querySelector('input[name="media[]"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "vendor_id": "8",
    "title": "First product from api",
    "description": "description here",
    "price": "5000",
    "status": "Published",
    "type": "product",
    "updated_at": "2020-12-23T02:28:23.000000Z",
    "created_at": "2020-12-23T02:28:23.000000Z",
    "id": 5,
    "category_ids": [
        1
    ],
    "address_ids": [
        6
    ],
    "media": [
        {
            "id": 37,
            "uuid": "aecd4910-724e-4ddb-9f52-846732aa84b5",
            "name": "69",
            "file_name": "69.jpg",
            "mime_type": "image\/jpeg",
            "size": 201597,
            "order_column": 30,
            "created_at": "2020-12-23T02:28:23.000000Z",
            "updated_at": "2020-12-23T02:28:23.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/37\/69.jpg",
            "large": "http:\/\/cosmo.test\/storage\/37\/69.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/37\/69.jpg",
            "small": "http:\/\/cosmo.test\/storage\/37\/69.jpg",
            "collection": "products"
        }
    ],
    "categories": [
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
                "taxable_id": 5,
                "taxonomy_id": 1,
                "taxable_type": "product"
            }
        }
    ],
    "addresses": [
        {
            "id": 6,
            "addressable_type": "vendor",
            "addressable_id": 8,
            "title": null,
            "city_id": 1,
            "address_1": "First street to the left",
            "address_2": "Hello world",
            "postal_code": "656598",
            "lat": null,
            "lng": null,
            "type": 1,
            "created_at": "2020-12-10T01:29:57.000000Z",
            "updated_at": "2020-12-10T01:29:57.000000Z",
            "pivot": {
                "product_id": 5,
                "address_id": 6
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
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-POSTapi-v1-products" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-products"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-products"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-products" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-products"></code></pre>
</div>
<form id="form-POSTapi-v1-products" data-method="POST" data-path="api/v1/products" data-authed="1" data-hasfiles="1" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"multipart\/form-data","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-products', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-products" onclick="tryItOut('POSTapi-v1-products');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-products" onclick="cancelTryOut('POSTapi-v1-products');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-products" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/products</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-products" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-products" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="vendor_id" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>title</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="title" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>
Product title 191 characters max.
</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="description" data-endpoint="POSTapi-v1-products" data-component="body"  hidden>
<br>
Product description 2000 characters max.
</p>
<p>
<b><code>price</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="price" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>
Product price 100000000 max.
</p>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="status" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>
`Published`, `Draft`.
</p>
<p>
<b><code>category_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="category_ids.0" data-endpoint="POSTapi-v1-products" data-component="body"  hidden>
<input type="number" name="category_ids.1" data-endpoint="POSTapi-v1-products" data-component="body" hidden>
<br>

</p>
<p>
<b><code>address_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="address_ids.0" data-endpoint="POSTapi-v1-products" data-component="body"  hidden>
<input type="number" name="address_ids.1" data-endpoint="POSTapi-v1-products" data-component="body" hidden>
<br>

</p>
<p>
<details>
<summary>
<b><code>media</code></b>&nbsp;&nbsp;<small>file[]</small>  &nbsp;
<br>
The value must be an image.
</summary>
<br>
<p>
<b><code>media[].*</code></b>&nbsp;&nbsp;<small>image</small>  &nbsp;
<input type="text" name="media.0.*" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>
Product image 10MB max.
</p>
</details>
</p>
<p>
<b><code>terms</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="terms" data-endpoint="POSTapi-v1-products" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>
The value must be one of <code>product</code> or <code>service</code>.
</p>
<p>
<b><code>review</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="review" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>
The value must be one of <code>Approved</code>, <code>Blocked</code>, or <code>Pending</code>.
</p>
<p>
<b><code>category_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="category_id" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>
Category id for this product.
</p>
<p>
<b><code>media[]</code></b>&nbsp;&nbsp;<small>required</small>     <i>optional</i> &nbsp;
<input type="text" name="media.0" data-endpoint="POSTapi-v1-products" data-component="body"  hidden>
<br>
array An array of product images.
</p>
<p>
<details>
<summary>
<b><code>keep_media</code></b>&nbsp;&nbsp;<small>array</small>  &nbsp;
<br>
Array of media ids to keep (Update only).
</summary>
<br>
<p>
<b><code>keep_media.*</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="keep_media.*" data-endpoint="POSTapi-v1-products" data-component="body" required  hidden>
<br>
Media id returned from server.
</p>
</details>
</p>

</form>


## Update product

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/products/facilis" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -F "vendor_id=14" \
    -F "title=et" \
    -F "description=molestiae" \
    -F "price=aliquid" \
    -F "status=commodi" \
    -F "category_ids[]=14" \
    -F "address_ids[]=4" \
    -F "terms=dolor" \
    -F "type=service" \
    -F "review=Blocked" \
    -F "category_id=15" \
    -F "media[]=omnis" \
    -F "keep_media=vel" \
    -F "media[]=@C:\Users\Saif\AppData\Local\Temp\phpBFA3.tmp" 
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/products/facilis"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
    "Accept-Language": "en",
};

const body = new FormData();
body.append('vendor_id', '14');
body.append('title', 'et');
body.append('description', 'molestiae');
body.append('price', 'aliquid');
body.append('status', 'commodi');
body.append('category_ids[]', '14');
body.append('address_ids[]', '4');
body.append('terms', 'dolor');
body.append('type', 'service');
body.append('review', 'Blocked');
body.append('category_id', '15');
body.append('media[]', 'omnis');
body.append('keep_media', 'vel');
body.append('media[]', document.querySelector('input[name="media[]"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "vendor_id": "8",
    "title": "First product from api",
    "description": "description here",
    "price": "5000",
    "status": "Published",
    "type": "product",
    "updated_at": "2020-12-23T02:28:23.000000Z",
    "created_at": "2020-12-23T02:28:23.000000Z",
    "id": 5,
    "category_ids": [
        1
    ],
    "address_ids": [
        6
    ],
    "media": [
        {
            "id": 37,
            "uuid": "aecd4910-724e-4ddb-9f52-846732aa84b5",
            "name": "69",
            "file_name": "69.jpg",
            "mime_type": "image\/jpeg",
            "size": 201597,
            "order_column": 30,
            "created_at": "2020-12-23T02:28:23.000000Z",
            "updated_at": "2020-12-23T02:28:23.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/37\/69.jpg",
            "large": "http:\/\/cosmo.test\/storage\/37\/69.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/37\/69.jpg",
            "small": "http:\/\/cosmo.test\/storage\/37\/69.jpg",
            "collection": "products"
        }
    ],
    "categories": [
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
                "taxable_id": 5,
                "taxonomy_id": 1,
                "taxable_type": "product"
            }
        }
    ],
    "addresses": [
        {
            "id": 6,
            "addressable_type": "vendor",
            "addressable_id": 8,
            "title": null,
            "city_id": 1,
            "address_1": "First street to the left",
            "address_2": "Hello world",
            "postal_code": "656598",
            "lat": null,
            "lng": null,
            "type": 1,
            "created_at": "2020-12-10T01:29:57.000000Z",
            "updated_at": "2020-12-10T01:29:57.000000Z",
            "pivot": {
                "product_id": 5,
                "address_id": 6
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
> Example response (401, Unauthenticated):

```json
{
    "message": "Unauthenticated."
}
```
<div id="execution-results-PUTapi-v1-products--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-products--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-products--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-products--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-products--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-products--id-" data-method="PUT" data-path="api/v1/products/{id}" data-authed="1" data-hasfiles="1" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"multipart\/form-data","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-products--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-products--id-" onclick="tryItOut('PUTapi-v1-products--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-products--id-" onclick="cancelTryOut('PUTapi-v1-products--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-products--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/products/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/products/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/products/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-products--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-products--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PUTapi-v1-products--id-" data-component="url" required  hidden>
<br>
Product id
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>vendor_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="vendor_id" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>title</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="title" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>
Product title 191 characters max.
</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="description" data-endpoint="PUTapi-v1-products--id-" data-component="body"  hidden>
<br>
Product description 2000 characters max.
</p>
<p>
<b><code>price</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="price" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>
Product price 100000000 max.
</p>
<p>
<b><code>status</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="status" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>
`Published`, `Draft`.
</p>
<p>
<b><code>category_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="category_ids.0" data-endpoint="PUTapi-v1-products--id-" data-component="body"  hidden>
<input type="number" name="category_ids.1" data-endpoint="PUTapi-v1-products--id-" data-component="body" hidden>
<br>

</p>
<p>
<b><code>address_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="address_ids.0" data-endpoint="PUTapi-v1-products--id-" data-component="body"  hidden>
<input type="number" name="address_ids.1" data-endpoint="PUTapi-v1-products--id-" data-component="body" hidden>
<br>

</p>
<p>
<details>
<summary>
<b><code>media</code></b>&nbsp;&nbsp;<small>file[]</small>  &nbsp;
<br>
The value must be an image.
</summary>
<br>
<p>
<b><code>media[].*</code></b>&nbsp;&nbsp;<small>image</small>  &nbsp;
<input type="text" name="media.0.*" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>
Product image 10MB max.
</p>
</details>
</p>
<p>
<b><code>terms</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="terms" data-endpoint="PUTapi-v1-products--id-" data-component="body"  hidden>
<br>

</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>
The value must be one of <code>product</code> or <code>service</code>.
</p>
<p>
<b><code>review</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="review" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>
The value must be one of <code>Approved</code>, <code>Blocked</code>, or <code>Pending</code>.
</p>
<p>
<b><code>category_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="category_id" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>
Category id for this product.
</p>
<p>
<b><code>media[]</code></b>&nbsp;&nbsp;<small>required</small>     <i>optional</i> &nbsp;
<input type="text" name="media.0" data-endpoint="PUTapi-v1-products--id-" data-component="body"  hidden>
<br>
array An array of product images.
</p>
<p>
<details>
<summary>
<b><code>keep_media</code></b>&nbsp;&nbsp;<small>array</small>  &nbsp;
<br>
Array of media ids to keep (Update only).
</summary>
<br>
<p>
<b><code>keep_media.*</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="keep_media.*" data-endpoint="PUTapi-v1-products--id-" data-component="body" required  hidden>
<br>
Media id returned from server.
</p>
</details>
</p>

</form>


## Delete product

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X DELETE \
    "http://guapa.com.sa/api/v1/products/ut" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/products/ut"
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
    "message": "Product deleted successfully",
    "id": 1
}
```
> Example response (404, Product not found):

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
<div id="execution-results-DELETEapi-v1-products--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-products--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-products--id-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-products--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-products--id-"></code></pre>
</div>
<form id="form-DELETEapi-v1-products--id-" data-method="DELETE" data-path="api/v1/products/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-products--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-products--id-" onclick="tryItOut('DELETEapi-v1-products--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-products--id-" onclick="cancelTryOut('DELETEapi-v1-products--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-products--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/products/{id}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-products--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-products--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="DELETEapi-v1-products--id-" data-component="url" required  hidden>
<br>
Product id
</p>
</form>



