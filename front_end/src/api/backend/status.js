import { URL_BACK_STATUS_COLLECTION } from '../../constants/urlsBack';
import backendAxiosClient from './backendAxiosClient';

export const statusApi = {
    getAll: () => {
        return backendAxiosClient.get(URL_BACK_STATUS_COLLECTION);
    },
};
