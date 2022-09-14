import { Base64 } from 'js-base64';

import {
    URL_BACK_ORDER_COLLECTION,
    URL_BACK_ORDER_ITEM,
    URL_BACK_ORDER_MAKE_RETURN_ORDER,
    URL_BACK_ORDER_PAYMENT_ITEM,
    URL_BACK_ORDER_UPDATE_STATUS,
} from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const orderApi = {
    getAll: (email, own = true) => {
        return backendAxiosClient.get(URL_BACK_ORDER_COLLECTION, {
            params: { 
                email: Base64.encodeURL(email),
                own: own
            },
        });
    },
    patch: (orderReference, values) => {
        return backendAxiosClient.patch(
            URL_BACK_ORDER_ITEM.replace('{orderReference}', orderReference),
            values,
        );
    },
    post: (order) => {
        return backendAxiosClient.post(URL_BACK_ORDER_COLLECTION, order);
    },
    updateState: (orderReference) => {
        return backendAxiosClient.post(
            URL_BACK_ORDER_UPDATE_STATUS.replace('{orderReference}', orderReference),
        );
    },
    get: (orderReference) => {
        return backendAxiosClient.get(
            URL_BACK_ORDER_ITEM.replace('{orderReference}', orderReference),
        );
    },
    getPaymentLink: (orderReference) => {
        return backendAxiosClient.get(
            URL_BACK_ORDER_PAYMENT_ITEM.replace('{orderReference}', orderReference),
        );
    },
    makeReturnOrder: (orderReference, values) => {
        return backendAxiosClient.post(
            URL_BACK_ORDER_MAKE_RETURN_ORDER.replace('{orderReference}', orderReference),
            values,
        );
    },
};
