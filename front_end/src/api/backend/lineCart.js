import { Base64 } from 'js-base64';

import { URL_BACK_CART_COLLECTION, URL_BACK_CART_ITEM } from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const lineCartApi = {
    getCollection: (email) => {
        return backendAxiosClient.get(URL_BACK_CART_COLLECTION, {
            params: { email: Base64.encodeURL(email) },
        });
    },
    addToCart: (values) => {
        return backendAxiosClient.post(URL_BACK_CART_COLLECTION, values);
    },
    patch: (id, values) => {
        return backendAxiosClient.patch(URL_BACK_CART_ITEM.replace('{id}', id), values);
    },
    delete: (id) => {
        return backendAxiosClient.delete(URL_BACK_CART_ITEM.replace('{id}', id), {});
    },
    deleteAll: (email) => {
        return backendAxiosClient.delete(URL_BACK_CART_COLLECTION, {
            params: { email: Base64.encodeURL(email) },
        });
    },
};
