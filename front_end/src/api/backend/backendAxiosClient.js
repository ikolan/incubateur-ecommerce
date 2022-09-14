import axios from 'axios';

import { isAuthenticated } from '../../services/accountServices';
import { getToken, setToken } from '../../services/tokenServices';

/**
 * Instance axios to the BACKEND
 *
 * @author Peter Mollet
 */
const backendAxiosClient = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL,
});
export default backendAxiosClient;

/**
 * Interceptor of request to automatically put the JWTToken in the header
 *
 * @author Peter Mollet
 */
backendAxiosClient.interceptors.request.use((request) => {
    if (isAuthenticated()) {
        request.headers['Authorization'] = `Bearer ${getToken()}`;
    }

    if (request.method === 'patch') {
        request.headers['Content-Type'] = 'application/merge-patch+json';
    } else {
        request.headers['Content-Type'] = 'application/json';
    }

    return request;
});

/**
 * Interceptor of response, to see status code in the console and to handle the error
 *
 * @author Peter Mollet
 */
backendAxiosClient.interceptors.response.use(
    (response) => {
        console.log(response.status);

        if ('renewed-jwt' in response.headers) {
            setToken(response.headers['renewed-jwt']);
            console.log('JWT renewed');
        }

        return response;
    },
    (error) => {
        console.log(error);
        console.log(error.response);
        return Promise.reject(error);
    },
);
