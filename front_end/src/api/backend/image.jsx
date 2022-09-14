import {
    URL_BACK_IMAGE_COLLECTION,
    URL_BACK_IMAGE_ITEM,
    URL_BACK_IMAGE_ITEM_DATA,
} from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const imageApi = {
    getAll: (page) => {
        return backendAxiosClient.get(URL_BACK_IMAGE_COLLECTION, {
            params: { page: page },
        });
    },
    getSearch: (page, name) => {
        return backendAxiosClient.get(URL_BACK_IMAGE_COLLECTION, {
            params: { page: page, name: name },
        });
    },
    getData: (id) => {
        return backendAxiosClient.get(URL_BACK_IMAGE_ITEM_DATA.replace('{id}', id));
    },
    post: (name, data, onThen, onCatch) => {
        backendAxiosClient
            .post(URL_BACK_IMAGE_COLLECTION, { name: name })
            .then((response) => {
                backendAxiosClient
                    .post(
                        URL_BACK_IMAGE_ITEM_DATA.replace('{id}', response.data.id),
                        data,
                    )
                    .then(onThen)
                    .catch(onCatch);
            })
            .catch(onCatch);
    },
    delete: (id) => {
        return backendAxiosClient.delete(URL_BACK_IMAGE_ITEM.replace('{id}', id));
    },
};
