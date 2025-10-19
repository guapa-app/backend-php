import { apiUrl } from '../utils/common'

export default {
    login: ({ username, password }) =>  {
        const request = new Request(apiUrl('auth/login'), {
            method: 'POST',
            body: JSON.stringify({ email: username, password }),
            headers: new Headers({
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }),
        })

        return fetch(request).then(response => {
            if (response.status < 200 || response.status >= 300) {
                throw new Error(response.statusText)
            }
            return response.json()
        }).then(user => {
            localStorage.setItem('admin', JSON.stringify(user))
        })
    },
    logout: () => {
        localStorage.removeItem('admin')
        return Promise.resolve()
    },
    checkError: error => {
        const status = error.status
        if (status === 401) {
            localStorage.removeItem('admin')
            return Promise.reject()
        }
        return Promise.resolve()
    },
    checkAuth: () => !!localStorage.getItem('admin')
        ? Promise.resolve()
        : Promise.reject(),
    getPermissions: () => {
        return Promise.resolve('admin')
        // const role = localStorage.getItem('permissions');
        // return role ? Promise.resolve(role) : Promise.reject();
    }
}
