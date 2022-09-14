import { URL_BACK_TAG_COLLECTION, URL_BACK_TAG_ITEM } from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const tagApi = {
    getAll: () => {
        return backendAxiosClient.get(URL_BACK_TAG_COLLECTION);
    },
    post: (label) => {
        return backendAxiosClient.post(URL_BACK_TAG_COLLECTION, { label: label });
    },
    patch: (id, label) => {
        return backendAxiosClient.patch(URL_BACK_TAG_ITEM.replace('{id}', id), {
            label: label,
        });
    },
    delete: (id) => {
        return backendAxiosClient.delete(URL_BACK_TAG_ITEM.replace('{id}', id));
    },
};
