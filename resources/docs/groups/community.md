# Community


## Posts list




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/posts?category_id=1&page=1&perPage=15&keyword=Liver&most_viewed=iure&most_liked=est&sort=created_at&order=DESC" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/posts"
);

let params = {
    "category_id": "1",
    "page": "1",
    "perPage": "15",
    "keyword": "Liver",
    "most_viewed": "iure",
    "most_liked": "est",
    "sort": "created_at",
    "order": "DESC",
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
{
    "current_page": 1,
    "data": [
        {
            "id": 6,
            "admin_id": 2,
            "category_id": 1,
            "title": "New post 2",
            "content": "<p>asdf asdfas dfasfd<\/p>",
            "status": 1,
            "created_at": "2020-11-28T02:31:08.000000Z",
            "updated_at": "2020-11-28T02:31:08.000000Z",
            "comments_count": 1,
            "likes_count": 628,
            "admin": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            },
            "category": {
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
                "updated_at": "2020-11-25T20:14:14.000000Z"
            },
            "media": [
                {
                    "id": 30,
                    "uuid": "50a4a759-45c2-442b-89f7-2be5b87c66a0",
                    "name": "83228905_1609860472511850_5851706938651312128_o",
                    "file_name": "83228905_1609860472511850_5851706938651312128_o.jpg",
                    "mime_type": "image\/jpeg",
                    "size": 63436,
                    "order_column": 23,
                    "created_at": "2020-11-28T02:31:08.000000Z",
                    "updated_at": "2020-11-28T02:31:09.000000Z",
                    "url": "http:\/\/cosmo.test\/storage\/30\/83228905_1609860472511850_5851706938651312128_o.jpg",
                    "large": "http:\/\/cosmo.test\/storage\/30\/conversions\/83228905_1609860472511850_5851706938651312128_o-large.jpg",
                    "medium": "http:\/\/cosmo.test\/storage\/30\/conversions\/83228905_1609860472511850_5851706938651312128_o-medium.jpg",
                    "small": "http:\/\/cosmo.test\/storage\/30\/conversions\/83228905_1609860472511850_5851706938651312128_o-small.jpg",
                    "collection": "posts"
                }
            ]
        },
        {
            "id": 5,
            "admin_id": 2,
            "category_id": 1,
            "title": "First event",
            "content": "<p>Content content<\/p>",
            "status": 1,
            "created_at": "2020-11-28T02:28:30.000000Z",
            "updated_at": "2020-11-28T02:28:30.000000Z",
            "comments_count": 0,
            "likes_count": 929,
            "admin": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            },
            "category": {
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
                "updated_at": "2020-11-25T20:14:14.000000Z"
            },
            "media": [
                {
                    "id": 28,
                    "uuid": "5e21ffe9-afb8-45dd-8241-3fde7770e3ba",
                    "name": "EcnDivzXkAAMJ0-",
                    "file_name": "EcnDivzXkAAMJ0-.jpg",
                    "mime_type": "image\/jpeg",
                    "size": 67298,
                    "order_column": 22,
                    "created_at": "2020-11-28T02:30:17.000000Z",
                    "updated_at": "2020-11-28T02:30:19.000000Z",
                    "url": "http:\/\/cosmo.test\/storage\/28\/EcnDivzXkAAMJ0-.jpg",
                    "large": "http:\/\/cosmo.test\/storage\/28\/conversions\/EcnDivzXkAAMJ0--large.jpg",
                    "medium": "http:\/\/cosmo.test\/storage\/28\/conversions\/EcnDivzXkAAMJ0--medium.jpg",
                    "small": "http:\/\/cosmo.test\/storage\/28\/conversions\/EcnDivzXkAAMJ0--small.jpg",
                    "collection": "posts"
                }
            ]
        },
        {
            "id": 4,
            "admin_id": 2,
            "category_id": 1,
            "title": "First event",
            "content": "<p>Content content<\/p>",
            "status": 1,
            "created_at": "2020-11-28T02:27:42.000000Z",
            "updated_at": "2020-11-28T02:27:42.000000Z",
            "comments_count": 0,
            "likes_count": 476,
            "admin": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            },
            "category": {
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
                "updated_at": "2020-11-25T20:14:14.000000Z"
            },
            "media": []
        },
        {
            "id": 3,
            "admin_id": 2,
            "category_id": 1,
            "title": "New post",
            "content": "<p>Post content very very long here<\/p>",
            "status": 1,
            "created_at": "2020-11-28T02:22:23.000000Z",
            "updated_at": "2020-11-28T02:22:23.000000Z",
            "comments_count": 2,
            "likes_count": 817,
            "admin": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            },
            "category": {
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
                "updated_at": "2020-11-25T20:14:14.000000Z"
            },
            "media": [
                {
                    "id": 31,
                    "uuid": "f39dc70f-3eb2-4f7b-bdd6-946ca46f7375",
                    "name": "83228905_1609860472511850_5851706938651312128_o",
                    "file_name": "83228905_1609860472511850_5851706938651312128_o.jpg",
                    "mime_type": "image\/jpeg",
                    "size": 63436,
                    "order_column": 24,
                    "created_at": "2020-11-28T02:55:01.000000Z",
                    "updated_at": "2020-11-28T02:55:01.000000Z",
                    "url": "http:\/\/cosmo.test\/storage\/31\/83228905_1609860472511850_5851706938651312128_o.jpg",
                    "large": "http:\/\/cosmo.test\/storage\/31\/83228905_1609860472511850_5851706938651312128_o.jpg",
                    "medium": "http:\/\/cosmo.test\/storage\/31\/83228905_1609860472511850_5851706938651312128_o.jpg",
                    "small": "http:\/\/cosmo.test\/storage\/31\/83228905_1609860472511850_5851706938651312128_o.jpg",
                    "collection": "posts"
                }
            ]
        },
        {
            "id": 2,
            "admin_id": 2,
            "category_id": 1,
            "title": "New post",
            "content": "<p>Post content very very long here<\/p>",
            "status": 1,
            "created_at": "2020-11-28T02:21:38.000000Z",
            "updated_at": "2020-11-28T02:21:38.000000Z",
            "comments_count": 0,
            "likes_count": 721,
            "admin": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            },
            "category": {
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
                "updated_at": "2020-11-25T20:14:14.000000Z"
            },
            "media": []
        },
        {
            "id": 1,
            "admin_id": 2,
            "category_id": 1,
            "title": "New post",
            "content": "<p>Post content very very long here<\/p>",
            "status": 1,
            "created_at": "2020-11-28T02:19:40.000000Z",
            "updated_at": "2020-11-28T02:19:40.000000Z",
            "comments_count": 4,
            "likes_count": 51,
            "admin": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            },
            "category": {
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
                "updated_at": "2020-11-25T20:14:14.000000Z"
            },
            "media": [
                {
                    "id": 32,
                    "uuid": "e52345b0-aad4-40cf-bd6b-a14cd50a9083",
                    "name": "Ee8LPz4XsAA5QgA",
                    "file_name": "Ee8LPz4XsAA5QgA.jpg",
                    "mime_type": "image\/jpeg",
                    "size": 91255,
                    "order_column": 25,
                    "created_at": "2020-11-28T03:10:58.000000Z",
                    "updated_at": "2020-11-28T03:10:58.000000Z",
                    "url": "http:\/\/cosmo.test\/storage\/32\/Ee8LPz4XsAA5QgA.jpg",
                    "large": "http:\/\/cosmo.test\/storage\/32\/Ee8LPz4XsAA5QgA.jpg",
                    "medium": "http:\/\/cosmo.test\/storage\/32\/Ee8LPz4XsAA5QgA.jpg",
                    "small": "http:\/\/cosmo.test\/storage\/32\/Ee8LPz4XsAA5QgA.jpg",
                    "collection": "posts"
                }
            ]
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/posts?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/posts?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/posts?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/posts",
    "per_page": "10",
    "prev_page_url": null,
    "to": 6,
    "total": 6
}
```
<div id="execution-results-GETapi-v1-posts" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-posts"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts"></code></pre>
</div>
<div id="execution-error-GETapi-v1-posts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts"></code></pre>
</div>
<form id="form-GETapi-v1-posts" data-method="GET" data-path="api/v1/posts" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-posts" onclick="tryItOut('GETapi-v1-posts');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-posts" onclick="cancelTryOut('GETapi-v1-posts');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-posts" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/posts</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>category_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="category_id" data-endpoint="GETapi-v1-posts" data-component="query"  hidden>
<br>
Get Posts for specific user.
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-posts" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-posts" data-component="query"  hidden>
<br>
Results to fetch per page
</p>
<p>
<b><code>keyword</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="keyword" data-endpoint="GETapi-v1-posts" data-component="query"  hidden>
<br>
Search posts.
</p>
<p>
<b><code>most_viewed</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="most_viewed" data-endpoint="GETapi-v1-posts" data-component="query"  hidden>
<br>
Order posts by views.
</p>
<p>
<b><code>most_liked</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="most_liked" data-endpoint="GETapi-v1-posts" data-component="query"  hidden>
<br>
Order posts by likes.
</p>
<p>
<b><code>sort</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="sort" data-endpoint="GETapi-v1-posts" data-component="query"  hidden>
<br>
Field used to sort results (created_at).
</p>
<p>
<b><code>order</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="order" data-endpoint="GETapi-v1-posts" data-component="query"  hidden>
<br>
Order The sort order (DESC, ASC).
</p>
</form>


## Get post by id




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/posts/5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/posts/5"
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
    "id": 6,
    "admin_id": 2,
    "category_id": 1,
    "title": "New post 2",
    "content": "<p>asdf asdfas dfasfd<\/p>",
    "status": 1,
    "created_at": "2020-11-28T02:31:08.000000Z",
    "updated_at": "2020-11-28T02:31:08.000000Z",
    "comments_count": 1,
    "admin": {
        "id": 2,
        "name": "Admin",
        "email": "admin@cosmo.com",
        "created_at": "2020-10-30T16:02:18.000000Z",
        "updated_at": "2020-10-30T16:02:18.000000Z",
        "role": "superadmin"
    },
    "media": [
        {
            "id": 30,
            "uuid": "50a4a759-45c2-442b-89f7-2be5b87c66a0",
            "name": "83228905_1609860472511850_5851706938651312128_o",
            "file_name": "83228905_1609860472511850_5851706938651312128_o.jpg",
            "mime_type": "image\/jpeg",
            "size": 63436,
            "order_column": 23,
            "created_at": "2020-11-28T02:31:08.000000Z",
            "updated_at": "2020-11-28T02:31:09.000000Z",
            "url": "http:\/\/cosmo.test\/storage\/30\/83228905_1609860472511850_5851706938651312128_o.jpg",
            "large": "http:\/\/cosmo.test\/storage\/30\/conversions\/83228905_1609860472511850_5851706938651312128_o-large.jpg",
            "medium": "http:\/\/cosmo.test\/storage\/30\/conversions\/83228905_1609860472511850_5851706938651312128_o-medium.jpg",
            "small": "http:\/\/cosmo.test\/storage\/30\/conversions\/83228905_1609860472511850_5851706938651312128_o-small.jpg",
            "collection": "posts"
        }
    ],
    "category": {
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
        "updated_at": "2020-11-25T20:14:14.000000Z"
    }
}
```
> Example response (404, Post not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-GETapi-v1-posts--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-posts--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts--id-"></code></pre>
</div>
<div id="execution-error-GETapi-v1-posts--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts--id-"></code></pre>
</div>
<form id="form-GETapi-v1-posts--id-" data-method="GET" data-path="api/v1/posts/{id}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-posts--id-" onclick="tryItOut('GETapi-v1-posts--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-posts--id-" onclick="cancelTryOut('GETapi-v1-posts--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-posts--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/posts/{id}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-v1-posts--id-" data-component="url" required  hidden>
<br>
Post id.
</p>
</form>


## Comments list




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/comments?post_id=1&page=1&perPage=15" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/comments"
);

let params = {
    "post_id": "1",
    "page": "1",
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
{
    "current_page": 1,
    "data": [
        {
            "id": 7,
            "post_id": 1,
            "user_type": "admin",
            "user_id": 2,
            "content": "<p>sadfasdf<\/p>",
            "created_at": "2020-12-02T14:22:48.000000Z",
            "updated_at": "2020-12-02T14:22:48.000000Z",
            "user": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            }
        },
        {
            "id": 6,
            "post_id": 3,
            "user_type": "admin",
            "user_id": 2,
            "content": "<p>Comments<\/p>",
            "created_at": "2020-12-02T14:07:44.000000Z",
            "updated_at": "2020-12-02T14:07:44.000000Z",
            "user": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            }
        },
        {
            "id": 5,
            "post_id": 6,
            "user_type": "admin",
            "user_id": 2,
            "content": "<p>Hello world<\/p>",
            "created_at": "2020-12-02T14:06:44.000000Z",
            "updated_at": "2020-12-02T14:06:44.000000Z",
            "user": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            }
        },
        {
            "id": 4,
            "post_id": 1,
            "user_type": "admin",
            "user_id": 2,
            "content": "<p>New comment<\/p>",
            "created_at": "2020-11-28T03:12:20.000000Z",
            "updated_at": "2020-11-28T03:12:20.000000Z",
            "user": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            }
        },
        {
            "id": 3,
            "post_id": 1,
            "user_type": "admin",
            "user_id": 2,
            "content": "<p>asfd asfd asdf asdfasfd<\/p>",
            "created_at": "2020-11-28T03:11:39.000000Z",
            "updated_at": "2020-11-28T03:11:39.000000Z",
            "user": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            }
        },
        {
            "id": 2,
            "post_id": 1,
            "user_type": "admin",
            "user_id": 2,
            "content": "<p>asdfj lkajsdflk jaslkdjf lkja<\/p>",
            "created_at": "2020-11-28T03:08:16.000000Z",
            "updated_at": "2020-11-28T03:08:16.000000Z",
            "user": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            }
        },
        {
            "id": 1,
            "post_id": 3,
            "user_type": "admin",
            "user_id": 2,
            "content": "<p>Comment here<\/p>",
            "created_at": "2020-11-28T02:48:12.000000Z",
            "updated_at": "2020-11-28T02:48:12.000000Z",
            "user": {
                "id": 2,
                "name": "Admin",
                "email": "admin@cosmo.com",
                "created_at": "2020-10-30T16:02:18.000000Z",
                "updated_at": "2020-10-30T16:02:18.000000Z",
                "role": "superadmin"
            }
        }
    ],
    "first_page_url": "http:\/\/cosmo.test\/api\/v1\/comments?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/cosmo.test\/api\/v1\/comments?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http:\/\/cosmo.test\/api\/v1\/comments?page=1",
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
    "path": "http:\/\/cosmo.test\/api\/v1\/comments",
    "per_page": "10",
    "prev_page_url": null,
    "to": 7,
    "total": 7
}
```
<div id="execution-results-GETapi-v1-comments" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-comments"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-comments"></code></pre>
</div>
<div id="execution-error-GETapi-v1-comments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-comments"></code></pre>
</div>
<form id="form-GETapi-v1-comments" data-method="GET" data-path="api/v1/comments" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-comments', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-comments" onclick="tryItOut('GETapi-v1-comments');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-comments" onclick="cancelTryOut('GETapi-v1-comments');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-comments" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/comments</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>post_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="post_id" data-endpoint="GETapi-v1-comments" data-component="query"  hidden>
<br>
Get Comments for specific post.
</p>
<p>
<b><code>page</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page" data-endpoint="GETapi-v1-comments" data-component="query"  hidden>
<br>
Page number for pagination
</p>
<p>
<b><code>perPage</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="perPage" data-endpoint="GETapi-v1-comments" data-component="query"  hidden>
<br>
Results to fetch per page
</p>
</form>


## Create comment

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/comments" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"post_id":6,"content":"iusto"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/comments"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "post_id": 6,
    "content": "iusto"
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
    "post_id": "6",
    "content": "First comment",
    "user_id": 9,
    "user_type": "user",
    "updated_at": "2020-12-25T19:20:32.000000Z",
    "created_at": "2020-12-25T19:20:32.000000Z",
    "id": 8
}
```
> Example response (404, Post not found):

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
<div id="execution-results-POSTapi-v1-comments" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-comments"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-comments"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-comments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-comments"></code></pre>
</div>
<form id="form-POSTapi-v1-comments" data-method="POST" data-path="api/v1/comments" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-comments', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-comments" onclick="tryItOut('POSTapi-v1-comments');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-comments" onclick="cancelTryOut('POSTapi-v1-comments');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-comments" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/comments</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-comments" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-comments" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>post_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="post_id" data-endpoint="POSTapi-v1-comments" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="content" data-endpoint="POSTapi-v1-comments" data-component="body" required  hidden>
<br>

</p>

</form>


## Update comment

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X PUT \
    "http://guapa.com.sa/api/v1/comments/10" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"post_id":4,"content":"facilis"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/comments/10"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "post_id": 4,
    "content": "facilis"
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
    "post_id": "6",
    "content": "First comment",
    "user_id": 9,
    "user_type": "user",
    "updated_at": "2020-12-25T19:20:32.000000Z",
    "created_at": "2020-12-25T19:20:32.000000Z",
    "id": 8
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
> Example response (404, Comment not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-PUTapi-v1-comments--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-v1-comments--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-comments--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-v1-comments--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-comments--id-"></code></pre>
</div>
<form id="form-PUTapi-v1-comments--id-" data-method="PUT" data-path="api/v1/comments/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-comments--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-v1-comments--id-" onclick="tryItOut('PUTapi-v1-comments--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-v1-comments--id-" onclick="cancelTryOut('PUTapi-v1-comments--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-v1-comments--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/v1/comments/{id}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/v1/comments/{id}</code></b>
</p>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/comments/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-v1-comments--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-v1-comments--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="PUTapi-v1-comments--id-" data-component="url" required  hidden>
<br>
Comment id.
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>post_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="post_id" data-endpoint="PUTapi-v1-comments--id-" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="content" data-endpoint="PUTapi-v1-comments--id-" data-component="body" required  hidden>
<br>

</p>

</form>


## Delete comment

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X DELETE \
    "http://guapa.com.sa/api/v1/comments/10" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/comments/10"
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
    8
]
```
> Example response (404, Comment not found):

```json

{
    "message": "Not found message",
}
```
<div id="execution-results-DELETEapi-v1-comments--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-comments--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-comments--id-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-comments--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-comments--id-"></code></pre>
</div>
<form id="form-DELETEapi-v1-comments--id-" data-method="DELETE" data-path="api/v1/comments/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-comments--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-comments--id-" onclick="tryItOut('DELETEapi-v1-comments--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-comments--id-" onclick="cancelTryOut('DELETEapi-v1-comments--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-comments--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/comments/{id}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-comments--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-comments--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-v1-comments--id-" data-component="url" required  hidden>
<br>
Comment id.
</p>
</form>



