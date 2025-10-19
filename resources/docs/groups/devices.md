# Devices


## Add new device

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://guapa.com.sa/api/v1/devices" \
    -H "Authorization: Bearer {YOUR_AUTH_KEY}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Accept-Language: en" \
    -d '{"fcmtoken":"quo","guid":"consequuntur","type":"amet"}'

```

```javascript
const url = new URL(
    "http://guapa.com.sa/api/v1/devices"
);

let headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Accept-Language": "en",
};

let body = {
    "fcmtoken": "quo",
    "guid": "consequuntur",
    "type": "amet"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTapi-v1-devices" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-devices"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-devices"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-devices" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-devices"></code></pre>
</div>
<form id="form-POSTapi-v1-devices" data-method="POST" data-path="api/v1/devices" data-authed="1" data-hasfiles="0" data-headers='{"Authorization":"Bearer {YOUR_AUTH_KEY}","Content-Type":"application\/json","Accept":"application\/json","Accept-Language":"en"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-devices', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-devices" onclick="tryItOut('POSTapi-v1-devices');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-devices" onclick="cancelTryOut('POSTapi-v1-devices');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-devices" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/devices</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-devices" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-devices" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>fcmtoken</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="fcmtoken" data-endpoint="POSTapi-v1-devices" data-component="body" required  hidden>
<br>
Fcm token
</p>
<p>
<b><code>guid</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="guid" data-endpoint="POSTapi-v1-devices" data-component="body" required  hidden>
<br>
Unique identifier for device
</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="POSTapi-v1-devices" data-component="body" required  hidden>
<br>
Device type `android`, `ios`, `desktop`
</p>

</form>



