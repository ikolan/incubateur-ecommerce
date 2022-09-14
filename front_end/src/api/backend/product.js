import {
    URL_BACK_PRODUCT_COLLECTION,
    URL_BACK_PRODUCT_ITEM,
    URL_BACK_PRODUCT_PRICE_SLICE,
} from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const productApi = {
    getAll: (filters = [], page = 1, search = '') => {
        let urlParams = new URLSearchParams();
        filters.forEach((filter) => urlParams.set(filter.type, filter.value));
        if (page !== 1) {
            urlParams.set('page', page.toString());
        }
        if (search !== '') {
            urlParams.set('search', search);
        }

        return backendAxiosClient.get(
            URL_BACK_PRODUCT_COLLECTION + '?' + urlParams.toString(),
        );
    },
    getProduct: (reference) => {
        return backendAxiosClient.get(
            URL_BACK_PRODUCT_ITEM.replace('{reference}', reference),
        );
    },
    getPriceSlice: () => {
        return backendAxiosClient.get(URL_BACK_PRODUCT_PRICE_SLICE);
    },
    post: (values) => {
        return backendAxiosClient.post(URL_BACK_PRODUCT_COLLECTION, values);
    },
    patch: (id, values) => {
        return backendAxiosClient.patch(
            URL_BACK_PRODUCT_ITEM.replace('{reference}', id),
            values,
        );
    },
    delete: (reference) => {
        return backendAxiosClient.delete(
            URL_BACK_PRODUCT_ITEM.replace('{reference}', reference),
        );
    },
    getFrontProducts: () => {
        return backendAxiosClient.get(URL_BACK_PRODUCT_COLLECTION, {
            params: { frontProducts: true },
        });
    },
};
