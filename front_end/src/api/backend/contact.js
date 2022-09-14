import {
    URL_BACK_CONTACT_COLLECTION,
    URL_BACK_CONTACT_ITEM,
} from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const contactApi = {
    getPage: (page = 1) => {
        return backendAxiosClient.get(URL_BACK_CONTACT_COLLECTION, {
            params: { page: page },
        });
    },
    post: (values) => {
        return backendAxiosClient.post(URL_BACK_CONTACT_COLLECTION, values);
    },
    delete: (id) => {
        return backendAxiosClient.delete(URL_BACK_CONTACT_ITEM.replace('{id}', id));
    },
};
