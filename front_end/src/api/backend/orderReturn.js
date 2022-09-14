import {
    URL_BACK_ORDER_RETURNS_COLLECTION,
    URL_BACK_ORDER_RETURNS_ITEM,
} from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const orderReturnApi = {
    getAll: () => {
        return backendAxiosClient.get(URL_BACK_ORDER_RETURNS_COLLECTION);
    },
    delete: (id) => {
        return backendAxiosClient.delete(URL_BACK_ORDER_RETURNS_ITEM.replace('{id}', id));
    },
};
