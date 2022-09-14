import {
    URL_BACK_ADDRESS_COLLECTION,
    URL_BACK_ADDRESS_ITEM,
    URL_BACK_ADDRESS_MAIN,
    URL_BACK_ADDRESS_MAIN_ITEM,
} from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const addressApi = {
    getAll: () => {
        return backendAxiosClient.get(URL_BACK_ADDRESS_COLLECTION);
    },
    getOne: (id) => {
        return backendAxiosClient.get(URL_BACK_ADDRESS_ITEM.replace('{id}', id));
    },
    getMain: () => {
        return backendAxiosClient.get(URL_BACK_ADDRESS_MAIN);
    },
    post: (value) => {
        return backendAxiosClient.post(URL_BACK_ADDRESS_COLLECTION, value);
    },
    patch: (id, value) => {
        return backendAxiosClient.patch(URL_BACK_ADDRESS_ITEM.replace('{id}', id), value);
    },
    patchMain: (id) => {
        return backendAxiosClient.patch(URL_BACK_ADDRESS_MAIN_ITEM.replace('{id}', id));
    },
    delete: (id) => {
        return backendAxiosClient.delete(URL_BACK_ADDRESS_ITEM.replace('{id}', id), {});
    },
};
