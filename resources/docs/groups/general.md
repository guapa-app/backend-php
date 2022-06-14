# General


## Contact support




> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/contact" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"subject":"aut","body":"sit","phone":"ab"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/contact"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "subject": "aut",
    "body": "sit",
    "phone": "ab"
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
    "subject": "Hello cosmo",
    "body": "Please help me",
    "phone": "+201064956325",
    "user_id": 9,
    "updated_at": "2020-12-30T01:00:09.000000Z",
    "created_at": "2020-12-30T01:00:09.000000Z",
    "id": 1
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
<div id="execution-results-POSTapi-v1-contact" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-contact"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-contact"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-contact" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-contact"></code></pre>
</div>
<form id="form-POSTapi-v1-contact" data-method="POST" data-path="api/v1/contact" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-contact', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-contact" onclick="tryItOut('POSTapi-v1-contact');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-contact" onclick="cancelTryOut('POSTapi-v1-contact');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-contact" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/contact</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>subject</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="subject" data-endpoint="POSTapi-v1-contact" data-component="body" required  hidden>
<br>
Message subject
</p>
<p>
<b><code>body</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="body" data-endpoint="POSTapi-v1-contact" data-component="body" required  hidden>
<br>
Message body
</p>
<p>
<b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="phone" data-endpoint="POSTapi-v1-contact" data-component="body" required  hidden>
<br>
Phone number
</p>

</form>


## Application pages




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/pages" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/pages"
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

[]
```
<div id="execution-results-GETapi-v1-pages" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-pages"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-pages"></code></pre>
</div>
<div id="execution-error-GETapi-v1-pages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-pages"></code></pre>
</div>
<form id="form-GETapi-v1-pages" data-method="GET" data-path="api/v1/pages" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-pages', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-pages" onclick="tryItOut('GETapi-v1-pages');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-pages" onclick="cancelTryOut('GETapi-v1-pages');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-pages" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/pages</code></b>
</p>
</form>


## Application data




> Example request:

```bash
curl -X GET \
    -G "http://guapa.com.sa/api/v1/data" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en"
```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/data"
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
    "specialties": [
        {
            "id": 3,
            "title": {
                "ar": "Dolorum magni rerum",
                "en": "Ullam a in omnis qui"
            },
            "slug": "ullam-a-in-omnis-qui",
            "description": {
                "ar": "Maxime earum ut exce",
                "en": "Officia et sunt hic"
            },
            "font_icon": null,
            "type": "specialty",
            "parent_id": null,
            "created_at": "2021-11-15T15:11:59.000000Z",
            "updated_at": "2021-11-15T15:11:59.000000Z",
            "children": [],
            "icon": {
                "id": 5,
                "uuid": "f38a2fc5-c7f2-4a2c-842f-1878efc666a9",
                "name": "Document_alt",
                "file_name": "Document_alt.png",
                "mime_type": "image\/png",
                "size": 22942,
                "order_column": 5,
                "created_at": "2021-11-15T15:11:59.000000Z",
                "updated_at": "2021-11-15T15:11:59.000000Z",
                "url": "http:\/\/guapa.com.sa\/storage\/5\/Document_alt.png",
                "large": "http:\/\/guapa.com.sa\/storage\/5\/Document_alt.png",
                "medium": "http:\/\/guapa.com.sa\/storage\/5\/Document_alt.png",
                "small": "http:\/\/guapa.com.sa\/storage\/5\/Document_alt.png",
                "collection": "taxonomy_icons"
            }
        }
    ],
    "categories": [
        {
            "id": 1,
            "title": {
                "ar": "Non quia qui impedit",
                "en": "Quia quis quae optio"
            },
            "slug": "quia-quis-quae-optio",
            "description": {
                "ar": "Quae perferendis bla",
                "en": "Irure dignissimos te"
            },
            "font_icon": null,
            "type": "category",
            "parent_id": null,
            "created_at": "2021-11-15T15:11:44.000000Z",
            "updated_at": "2021-11-15T15:11:44.000000Z",
            "children": [
                {
                    "id": 5,
                    "title": {
                        "ar": "Et doloribus adipisi",
                        "en": "Lorem proident labo"
                    },
                    "slug": "lorem-proident-labo",
                    "description": {
                        "ar": "Illo nemo temporibus",
                        "en": "Natus velit soluta l"
                    },
                    "font_icon": null,
                    "type": "blog_category",
                    "parent_id": 1,
                    "created_at": "2021-12-22T15:51:44.000000Z",
                    "updated_at": "2021-12-22T15:51:44.000000Z",
                    "icon": {
                        "id": 9,
                        "uuid": "c86a1bfa-7502-4718-98c2-753a056125e7",
                        "name": "logo",
                        "file_name": "logo.png",
                        "mime_type": "image\/png",
                        "size": 1606,
                        "order_column": 8,
                        "created_at": "2021-12-22T15:51:45.000000Z",
                        "updated_at": "2021-12-22T15:51:45.000000Z",
                        "url": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/9\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=6eb98bdcc23f69a3b97583efee4f97a23a929d0b8aca776fb25ee641aeaa363c",
                        "large": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/9\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=6eb98bdcc23f69a3b97583efee4f97a23a929d0b8aca776fb25ee641aeaa363c",
                        "medium": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/9\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=6eb98bdcc23f69a3b97583efee4f97a23a929d0b8aca776fb25ee641aeaa363c",
                        "small": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/9\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=6eb98bdcc23f69a3b97583efee4f97a23a929d0b8aca776fb25ee641aeaa363c",
                        "collection": "taxonomy_icons"
                    }
                },
                {
                    "id": 6,
                    "title": {
                        "ar": "Et doloribus adipisi",
                        "en": "Lorem proident labo"
                    },
                    "slug": "lorem-proident-labo-1",
                    "description": {
                        "ar": "Illo nemo temporibus",
                        "en": "Natus velit soluta l"
                    },
                    "font_icon": null,
                    "type": "blog_category",
                    "parent_id": 1,
                    "created_at": "2021-12-22T15:59:29.000000Z",
                    "updated_at": "2021-12-22T15:59:29.000000Z",
                    "icon": {
                        "id": 10,
                        "uuid": "c3d6a9ca-2463-494f-960e-5bcaf0b6859b",
                        "name": "logo",
                        "file_name": "logo.png",
                        "mime_type": "image\/png",
                        "size": 1606,
                        "order_column": 9,
                        "created_at": "2021-12-22T15:59:29.000000Z",
                        "updated_at": "2021-12-22T15:59:29.000000Z",
                        "url": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/10\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=ade0bbb76c6d16c738a68c411a52299cb6fccc3b47c63a4cfcc25da97724f45a",
                        "large": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/10\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=ade0bbb76c6d16c738a68c411a52299cb6fccc3b47c63a4cfcc25da97724f45a",
                        "medium": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/10\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=ade0bbb76c6d16c738a68c411a52299cb6fccc3b47c63a4cfcc25da97724f45a",
                        "small": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/10\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=ade0bbb76c6d16c738a68c411a52299cb6fccc3b47c63a4cfcc25da97724f45a",
                        "collection": "taxonomy_icons"
                    }
                },
                {
                    "id": 7,
                    "title": {
                        "ar": "Et doloribus adipisi",
                        "en": "Lorem proident labo"
                    },
                    "slug": "lorem-proident-labo-2",
                    "description": {
                        "ar": "Illo nemo temporibus",
                        "en": "Natus velit soluta l"
                    },
                    "font_icon": null,
                    "type": "blog_category",
                    "parent_id": 1,
                    "created_at": "2021-12-22T15:59:59.000000Z",
                    "updated_at": "2021-12-22T15:59:59.000000Z",
                    "icon": {
                        "id": 11,
                        "uuid": "45b3536a-718a-4193-8f01-39939bb4cc70",
                        "name": "logo",
                        "file_name": "logo.png",
                        "mime_type": "image\/png",
                        "size": 1606,
                        "order_column": 10,
                        "created_at": "2021-12-22T15:59:59.000000Z",
                        "updated_at": "2021-12-22T15:59:59.000000Z",
                        "url": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/11\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=bd1846abe84552488d949d381ece8f722b37aedbccab329f048678e5025377ca",
                        "large": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/11\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=bd1846abe84552488d949d381ece8f722b37aedbccab329f048678e5025377ca",
                        "medium": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/11\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=bd1846abe84552488d949d381ece8f722b37aedbccab329f048678e5025377ca",
                        "small": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/11\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=bd1846abe84552488d949d381ece8f722b37aedbccab329f048678e5025377ca",
                        "collection": "taxonomy_icons"
                    }
                },
                {
                    "id": 8,
                    "title": {
                        "ar": "Et doloribus adipisi",
                        "en": "Lorem proident labo"
                    },
                    "slug": "lorem-proident-labo-3",
                    "description": {
                        "ar": "Illo nemo temporibus",
                        "en": "Natus velit soluta l"
                    },
                    "font_icon": null,
                    "type": "blog_category",
                    "parent_id": 1,
                    "created_at": "2021-12-22T16:01:12.000000Z",
                    "updated_at": "2021-12-22T16:01:12.000000Z",
                    "icon": {
                        "id": 12,
                        "uuid": "541180c6-389f-4e0d-9186-59e67b7366e6",
                        "name": "logo",
                        "file_name": "logo.png",
                        "mime_type": "image\/png",
                        "size": 1606,
                        "order_column": 11,
                        "created_at": "2021-12-22T16:01:12.000000Z",
                        "updated_at": "2021-12-22T16:01:12.000000Z",
                        "url": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/12\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=b543741bf55278fff336dd346feba04f1f2a01633a9871e98be0176c11910da2",
                        "large": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/12\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=b543741bf55278fff336dd346feba04f1f2a01633a9871e98be0176c11910da2",
                        "medium": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/12\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=b543741bf55278fff336dd346feba04f1f2a01633a9871e98be0176c11910da2",
                        "small": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/12\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=b543741bf55278fff336dd346feba04f1f2a01633a9871e98be0176c11910da2",
                        "collection": "taxonomy_icons"
                    }
                },
                {
                    "id": 9,
                    "title": {
                        "ar": "Et doloribus adipisi",
                        "en": "Lorem proident labo"
                    },
                    "slug": "lorem-proident-labo-4",
                    "description": {
                        "ar": "Illo nemo temporibus",
                        "en": "Natus velit soluta l"
                    },
                    "font_icon": null,
                    "type": "blog_category",
                    "parent_id": 1,
                    "created_at": "2021-12-22T16:01:35.000000Z",
                    "updated_at": "2021-12-22T16:01:35.000000Z",
                    "icon": {
                        "id": 13,
                        "uuid": "4baf6b92-47c5-49ee-9ab8-b367a18fc0e8",
                        "name": "logo",
                        "file_name": "logo.png",
                        "mime_type": "image\/png",
                        "size": 1606,
                        "order_column": 1,
                        "created_at": "2021-12-22T16:01:35.000000Z",
                        "updated_at": "2021-12-22T16:01:38.000000Z",
                        "url": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/13\/logo.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=0bee31787e838d0e3959820fbc179f3e90252c98974b1292f57a7822153c5b11",
                        "large": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/13\/conversions\/logo-large.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=ad72e7bc969ee23f9f85b4e31fc8c8fec02526383b4a2f2bca1cfa9010dc6099",
                        "medium": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/13\/conversions\/logo-medium.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=f1d59c207cf37d0ec244a397c08a06540b325a85d0dd00510e60fc612ec0ab30",
                        "small": "https:\/\/gouap.s3.us-east-2.amazonaws.com\/13\/conversions\/logo-small.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVHHBHO2XXIPYAQFG%2F20211231%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20211231T052427Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1200&X-Amz-Signature=cfb0c832f2320d26a07449d16c34b7ba64912d28145fe1a471640673ad1cfd2b",
                        "collection": "taxonomy_icons"
                    }
                }
            ],
            "icon": {
                "id": 3,
                "uuid": "aec8ea90-2e94-4c79-8c51-dbcfe318f9d6",
                "name": "Document_alt",
                "file_name": "Document_alt.png",
                "mime_type": "image\/png",
                "size": 22942,
                "order_column": 3,
                "created_at": "2021-11-15T15:11:44.000000Z",
                "updated_at": "2021-11-15T15:11:44.000000Z",
                "url": "http:\/\/guapa.com.sa\/storage\/3\/Document_alt.png",
                "large": "http:\/\/guapa.com.sa\/storage\/3\/Document_alt.png",
                "medium": "http:\/\/guapa.com.sa\/storage\/3\/Document_alt.png",
                "small": "http:\/\/guapa.com.sa\/storage\/3\/Document_alt.png",
                "collection": "taxonomy_icons"
            }
        }
    ],
    "blog_categories": [
        {
            "id": 2,
            "title": {
                "ar": "Fugiat beatae saepe",
                "en": "Rerum in voluptate a"
            },
            "slug": "rerum-in-voluptate-a",
            "description": {
                "ar": "Laborum Aut maiores",
                "en": "Tenetur cillum proid"
            },
            "font_icon": null,
            "type": "blog_category",
            "parent_id": null,
            "created_at": "2021-11-15T15:11:52.000000Z",
            "updated_at": "2021-11-15T15:11:52.000000Z",
            "children": [
                {
                    "id": 4,
                    "title": {
                        "ar": "Dignissimos id dele",
                        "en": "Quia qui voluptate s"
                    },
                    "slug": "quia-qui-voluptate-s",
                    "description": {
                        "ar": "Et consequatur Non",
                        "en": "Laboris rerum tempor"
                    },
                    "font_icon": null,
                    "type": "category",
                    "parent_id": 2,
                    "created_at": "2021-11-15T15:12:12.000000Z",
                    "updated_at": "2021-11-15T15:12:12.000000Z",
                    "icon": {
                        "id": 6,
                        "uuid": "76fa5f19-6f5d-4159-8b01-c01a742cbd69",
                        "name": "facebook",
                        "file_name": "facebook.png",
                        "mime_type": "image\/png",
                        "size": 23556,
                        "order_column": 6,
                        "created_at": "2021-11-15T15:12:12.000000Z",
                        "updated_at": "2021-11-15T15:12:12.000000Z",
                        "url": "http:\/\/guapa.com.sa\/storage\/6\/facebook.png",
                        "large": "http:\/\/guapa.com.sa\/storage\/6\/facebook.png",
                        "medium": "http:\/\/guapa.com.sa\/storage\/6\/facebook.png",
                        "small": "http:\/\/guapa.com.sa\/storage\/6\/facebook.png",
                        "collection": "taxonomy_icons"
                    }
                }
            ],
            "icon": {
                "id": 4,
                "uuid": "b439c7c8-e52b-4134-aba3-8c5517c1a4da",
                "name": "Document_alt",
                "file_name": "Document_alt.png",
                "mime_type": "image\/png",
                "size": 22942,
                "order_column": 4,
                "created_at": "2021-11-15T15:11:52.000000Z",
                "updated_at": "2021-11-15T15:11:52.000000Z",
                "url": "http:\/\/guapa.com.sa\/storage\/4\/Document_alt.png",
                "large": "http:\/\/guapa.com.sa\/storage\/4\/Document_alt.png",
                "medium": "http:\/\/guapa.com.sa\/storage\/4\/Document_alt.png",
                "small": "http:\/\/guapa.com.sa\/storage\/4\/Document_alt.png",
                "collection": "taxonomy_icons"
            }
        }
    ],
    "address_types": [
        {
            "id": 1,
            "name": "Service center"
        },
        {
            "id": 2,
            "name": "Sales outlet"
        },
        {
            "id": 3,
            "name": "Service and sales"
        },
        {
            "id": 4,
            "name": "Shipping"
        },
        {
            "id": 5,
            "name": "Billing"
        },
        {
            "id": 6,
            "name": "Primary"
        },
        {
            "id": 7,
            "name": "Website"
        }
    ],
    "vendor_types": [
        {
            "id": 0,
            "name": "hospital"
        },
        {
            "id": 1,
            "name": "clinic"
        },
        {
            "id": 2,
            "name": "doctor"
        }
    ],
    "cities": [
        {
            "id": 1,
            "name": {
                "ar": "Buffy Cervantes",
                "en": "Darius Franks"
            },
            "created_at": "2021-11-15T15:21:03.000000Z",
            "updated_at": "2021-11-15T15:21:03.000000Z"
        }
    ],
    "settings": [],
    "max_price": 923
}
```
<div id="execution-results-GETapi-v1-data" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-data"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-data"></code></pre>
</div>
<div id="execution-error-GETapi-v1-data" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-data"></code></pre>
</div>
<form id="form-GETapi-v1-data" data-method="GET" data-path="api/v1/data" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-data', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-data" onclick="tryItOut('GETapi-v1-data');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-data" onclick="cancelTryOut('GETapi-v1-data');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-data" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/data</code></b>
</p>
</form>



