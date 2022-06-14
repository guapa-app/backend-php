import { fetchUtils, HttpError } from 'react-admin'

export const httpClient = (url, options = {}) => {
    
    const user = localStorage.getItem('admin')
    const token = user ? JSON.parse(user).token.access_token: null

    if ( ! options) {
        options = {}
    }

    if ( ! options.headers) {
        options.headers = new Headers()
    }

    const contentType = options.headers.get('Content-Type')
    options.headers.set('Accept', 'application/json')
    options.headers.set('Authorization', 'Bearer ' + token)

    return myFetchJson(url, options)
}

export const Request = (url, options = {}) => {

    if ( ! options.headers) {
        options.headers = new Headers()
    }

    const user = localStorage.getItem('admin')
    const token = user ? JSON.parse(user).token.access_token: null

    const contentType = options.headers.get('Content-Type')
    options.headers.set('Accept', 'application/json')
    options.headers.set('Authorization', 'Bearer ' + token)

    // add your own headers here
    // options.headers.set('Content-Type', contentType || 'application/json')
    return fetch(url, options)
}

export const myFetchJson = (url, options = {}) => {
    
    const requestHeaders = (options.headers ||
        new Headers({
            Accept: 'application/json',
        }));
    if (
        !requestHeaders.has('Content-Type') &&
        !(options && options.body && options.body instanceof FormData)
    ) {
        requestHeaders.set('Content-Type', 'application/json');
    }
    if (options.user && options.user.authenticated && options.user.token) {
        requestHeaders.set('Authorization', options.user.token);
    }

    return fetch(url, { ...options, headers: requestHeaders })
        .then(response =>
            response.text().then(text => ({
                status: response.status,
                statusText: response.statusText,
                headers: response.headers,
                body: text,
            }))
        )
        .then(({ status, statusText, headers, body }) => {
            let json;
            try {
                json = JSON.parse(body);
            } catch (e) {
                // not json, no big deal
            }
            if (status < 200 || status >= 300) {
                let message
                if (json && json.errors) {
                    message = json.errors[Object.keys(json.errors)[0]][0]
                } else if (json && json.message) {
                    message = json.message
                }
                return Promise.reject(
                    new HttpError(
                        message || statusText,
                        status,
                        json
                    )
                );
            }
            return Promise.resolve({ status, headers, body, json });
        });
};
