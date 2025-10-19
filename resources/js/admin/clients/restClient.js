import { stringify } from 'query-string'

import moment from 'moment'

import { itemImageUrl, isObject, api_url as apiUrl, forQueryString } from '../utils/common'

import { httpClient } from '../clients/httpClient'

export default {
    getList: (resource, params) => {
        let { page, perPage } = params.pagination
        const { field, order } = params.sort
        if (perPage === 1000) {
            // Force to export all database records
            perPage = 1000000000000
        }

        // Get params without already handled params
        const { sort, pagination, ...myParams } = params

        const query = forQueryString({
            sort: field || 'id',
            order: order || 'DESC',
            page: page || 1,
            perPage: perPage || 10,
            ...myParams,
        })

        const url = `${apiUrl}/${resource}?${stringify(query)}`

        return httpClient(url).then(({ headers, json }) => {
            return ({
                data: json.data,
                total: json.total,
            })
        })
    },

    getOne: (resource, params) => {
        let url = `${apiUrl}/${resource}/${params.id}`
        url = params.endpointExt ? url + '/' + params.endpointExt : url
        if (params.params) {
            url = url + '?' + stringify(params.params)
        }

        return httpClient(url).then(({ json }) => ({
            data: json,
        }))
    },

    getMany: (resource, params) => {
        console.log(resource, params)
        // Apply ids filter only if we are not instructed
        // to fetch all records
        // However ids param is required for react-admin to
        // call this method
        if (params.ids && ! (params.filter && params.filter.all)) {
            params.filter = params.filter || {}
            params.filter.id = params.ids
        }

        let query = forQueryString({
            sort: params.sort ? params.sort.field : 'id',
            order: params.sort ? params.sort.order : 'DESC',
            ...params,
        })

        const url = `${apiUrl}/${resource}?${stringify(query)}`

        return httpClient(url).then(({ json }) => ({ data: json }))
    },

    getManyReference: (resource, params) => {
        const { page, perPage } = params.pagination
        const { field, order } = params.sort
        
        const myFilter = {
            ...params.filter,
            [params.target]: params.id,
        }

        const query = forQueryString({
            sort: field || 'id',
            order: order || 'DESC',
            page: page || 1,
            perPage: perPage || 10,
            filter: myFilter,
        })

        const url = `${apiUrl}/${resource}?${stringify(query)}`

        return httpClient(url).then(({ headers, json }) => ({
            data: json.data,
            total: json.total,
        }))
    },

    update: (resource, params) => {
        const url = `${apiUrl}/${resource}/${params.id}`
        const data = new FormData()
        let method = 'PUT'

        for (var key in params.data) {
            if (isObject(params.data[key]) && params.data[key].rawFile) {
                data.append(key, params.data[key].rawFile)
            } else if (key === 'media') {
                params.data[key].forEach((image, index) => {
                    if (image.id) {
                        data.append('keep_media['+index+']', image.id)
                    } else {
                        data.append('media['+index+']', image.rawFile)
                    }
                })
            } else if (params.data[key] && params.data[key].constructor === Array) {
                const param = params.data[key]
                param.forEach((one, index) => {
                    if (isObject(one)) {
                        for (var k in one) {
                            if ( !! one[k]) {
                                data.append(key + '[' + index + '][' + k + ']', one[k])
                            }
                        }
                    } else {
                        if ( !! one && one != 0 && one !== false) {
                            data.append(key + '[' + index + ']', one)
                        }
                    }
                })
            } else if (isObject(params.data[key])) {
                for (var k in params.data[key]) {
                    if ( ! params.data[key][k] && params.data[key][k] !== false) { continue }
                    if (isObject(params.data[key][k]) && params.data[key][k].rawFile) {
                        data.append(key + '[' + k + ']', params.data[key][k].rawFile);
                    } else if (params.data[key][k] === true) {
                        data.append(key + '[' + k + ']', '1')
                    } else if (params.data[key][k] === false) {
                        data.append(key + '[' + k + ']', '0')
                    } else {
                        data.append(key + '[' + k + ']', params.data[key][k])
                    }
                }
            } else if (params.data[key] === true) {
                data.append(key, '1')
            } else if (params.data[key] === false) {
                data.append(key, '0')
            } else if (!! params.data[key]) {
                data.append(key, params.data[key])
            }
        }

        method = 'POST'
        data.append('_method', 'put')

        return httpClient(`${apiUrl}/${resource}/${params.id}`, {
            method,
            body: data,
        }).then(({ json }) => ({ data: json }))
    },

    updateMany: (resource, params) => {
        const query = {
            filter: JSON.stringify({ id: params.ids}),
        };
        return httpClient(`${apiUrl}/${resource}?${stringify(query)}`, {
            method: 'PUT',
            body: JSON.stringify(params.data),
        }).then(({ json }) => ({ data: json }));
    },

    create: (resource, params) => {
        const url = `${apiUrl}/${resource}`
        const data = new FormData()
        let method = 'POST'
        
        for (var key in params.data) {
            if (isObject(params.data[key]) && params.data[key].rawFile) {
                data.append(key, params.data[key].rawFile)
            } else if (key === 'media') {
                params.data[key].forEach((image, index) => {
                    data.append('media['+index+']', image.rawFile)
                })
            } else if (params.data[key] && params.data[key].constructor === Array) {
                const param = params.data[key]
                param.forEach((one, index) => {
                    if (isObject(one)) {
                        for (var k in one) {
                            if ( !! one[k]) {
                                data.append(key + '[' + index + '][' + k + ']', one[k])
                            }
                        }
                    } else {
                        if ( !! one) {
                            data.append(key + '[' + index + ']', one)
                        }
                    }
                })
            } else if (isObject(params.data[key])) {
                for (var k in params.data[key]) {
                    if ( ! params.data[key][k] && params.data[key][k] !== false) { continue }
                    if (isObject(params.data[key][k]) && params.data[key][k].rawFile) {
                        data.append(key + '[' + k + ']', params.data[key][k].rawFile);
                    } else if (params.data[key][k] === true) {
                        data.append(key + '[' + k + ']', '1')
                    } else if (params.data[key][k] === false) {
                        data.append(key + '[' + k + ']', '0')
                    } else {
                        data.append(key + '[' + k + ']', params.data[key][k])
                    }
                }
            } else if (params.data[key] === true) {
                data.append(key, '1')
            } else if (params.data[key] === false) {
                data.append(key, '0')
            }  else if (!! params.data[key]) {
                data.append(key, params.data[key])
            }
        }
        
        return httpClient(`${apiUrl}/${resource}`, {
            method,
            body: data,
        }).then(({ json }) => ({ data: json }))
    },

    delete: (resource, params) => {
        return httpClient(`${apiUrl}/${resource}/${params.id}`, {
            method: 'DELETE',
        }).then(({ json }) => ({ data: json }))
    },

    deleteMany: (resource, params) => {
        const url = `${apiUrl}/${resource}/${encodeURIComponent(JSON.stringify(params.ids))}`
        return httpClient(url, {
            method: 'DELETE',
        }).then(({ json }) => ({ data: json }))
    }

}
