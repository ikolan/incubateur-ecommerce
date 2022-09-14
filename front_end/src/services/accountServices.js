import { getPayloadToken, getToken } from './tokenServices';

/**
 * To get the email of the current user.
 *
 * @author Nicolas Benoit
 */
export function accountEmail() {
    const payload = getPayloadToken();
    return payload.username;
}

/**
 * To get all the roles of the current user
 *
 * @return {Array} roles of the current user
 * @author Peter Mollet
 */
export function accountRoles() {
    return getPayloadToken().roles;
}

/**
 * To get the login of the current user
 *
 * @return {string} login of the current user
 * @author Peter Mollet
 */
export function accountLogin() {
    const payload = getPayloadToken();
    return payload.sub;
}

export function hasRole(role) {
    return accountRoles().includes(role);
}

/**
 * To check if the current user is authenticated
 * Check the token, and it's validity
 *
 * @return {boolean} true if user is authenticated
 * @author Peter Mollet
 */
export function isAuthenticated() {
    try {
        const token = getToken();
        //  console.log('token', token);
        const payload = getPayloadToken();
        // console.log('payload', payload);
        const roles = payload.roles;
        // console.log('roles', roles);
        const expirationDate = payload.exp;
        // console.log('expiration', expirationDate);
        const login = payload.iat;
        // console.log('login', login);
        const dateNow = new Date();
        return token && roles.length > 0 && login && expirationDate < dateNow.getTime();
    } catch {
        //  console.log('CATCH FALSE');
        return false;
    }
}
