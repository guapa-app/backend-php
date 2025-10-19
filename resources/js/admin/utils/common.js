import _ from 'lodash'

export const base_url = window.baseUrl

export const api_url = base_url + '/admin-api'

export const siteUrl = uri => {
	uri = uri ? '/' + uri : ''
	return base_url + uri
}

export const apiUrl = uri => {
	uri = uri ? '/' + uri : ''
	return api_url + uri
}

export const ucFirst = str => {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

export const myNumber = val => isNaN(val) ? 0 : Math.round(Number(val) * 100)/100

export const itemImageUrl = (userId, imageName, resource) => {
	return siteUrl('storage/users/' + userId + '/' + resource + '/' + imageName)
}

export const shopLogoUrl = (userId, imageName) => {
  return siteUrl('storage/users/' + userId + '/shops/' + imageName)
}

export const userAvatarUrl = (userId, imageName) => {
  return siteUrl('storage/users/' + userId + '/avatars/' + imageName)
}

export const isObject = obj => {
  return _.isPlainObject(obj)
}

export const joinStrings = (...args) => {
    let returnStr = ''
    let joinString = ' - '
    args.forEach(str => {
        if (!!str && returnStr != '') {
            returnStr += joinString + str
        } else if (!!str) {
            returnStr += str
        }
    })

    return returnStr
}

const hasSquareBrackets = str => str.includes('[') && str.includes(']')

const prependObjectKey = (k, obj) => {
  if ( ! isObject(obj)) {
    return {}
  }

  const prependedObj = {}
  for (var key in obj) {
    if (hasSquareBrackets(key)) {
      // Get the base key
      const parts = key.split('[')
      const basekey = parts[0]
      const newKey = parts.join('[').replace(basekey, k + '[' + basekey + ']')
      prependedObj[newKey] = obj[key]
    } else {
      // The base key is key
      prependedObj[k + '[' + key + ']'] = obj[key]
    }
  }

  return prependedObj
}

/**
 * Get one level key: value object from any object
 * for query-string stringify method
 * We will handle only one level for non-object values
 * And all levels for nested objects
 * @param  {object} obj
 * @return {object}
 */
export const forQueryString = obj => {
  if ( ! isObject(obj)) {
    return {}
  }

  // Result obj
  let normalized = {}
  for (var key in obj) {
    if ( ! obj[key]) { continue }
    if (obj[key].constructor === Array) {
      // Expected to be key: [val1, val2, val3]
      for (var i = 0; i < obj[key].length; i++) {
        normalized[key+'['+i+']'] = obj[key][i]
      }
    } else if (isObject(obj[key])) {
      const normalizedObj = forQueryString(obj[key])
      normalized = { ...normalized, ...prependObjectKey(key, normalizedObj)}
    } else {
      normalized[key] = obj[key]
    }
  }

  return normalized
}
