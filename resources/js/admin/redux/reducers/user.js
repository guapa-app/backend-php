import { USER_LOGIN_SUCCESS } from 'react-admin'

const user = localStorage.getItem('admin')
const initialState = user ? JSON.parse(user) : null

export default (previousState = initialState, { type, payload }) => {
    if (type === USER_LOGIN_SUCCESS) {
    	localStorage.setItem('admin', JSON.stringify(payload))
        return { ...payload }
    }
    return previousState
}
