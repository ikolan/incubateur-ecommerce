import { Buffer } from 'buffer';
import { Base64 } from 'js-base64';

import {
    URL_BACK_USER_ACTIVATION,
    URL_BACK_USER_COLLECTION,
    URL_BACK_USER_CURRENT,
    URL_BACK_USER_DEACTIVATION,
    URL_BACK_USER_GET_BY_RESET_KEY,
    URL_BACK_USER_ITEM,
    URL_BACK_USER_LOGIN,
    URL_BACK_USER_REACTIVATION_REQUEST,
    URL_BACK_USER_RESET_KEY,
} from '../../constants/urlsBack';
import { getPayloadToken } from '../../services/tokenServices';
import backendAxiosClient from './backendAxiosClient';

export const userApi = {
    post: (values) => {
        return backendAxiosClient.post(URL_BACK_USER_COLLECTION, values);
    },
    patch: (id, values) => {
        return backendAxiosClient.patch(URL_BACK_USER_ITEM.replace('{id}', id), values);
    },
    patchEmail: (email, values) => {
        return backendAxiosClient.patch(
            URL_BACK_USER_ITEM.replace('{id}', Base64.encodeURL(email)),
            values,
        );
    },
    login: (value) => {
        return backendAxiosClient.post(URL_BACK_USER_LOGIN, value);
    },
    activate: (activationKey) => {
        return backendAxiosClient.post(URL_BACK_USER_ACTIVATION, {
            activationKey: activationKey,
        });
    },
    generateResetKey: (email) => {
        return backendAxiosClient.post(
            URL_BACK_USER_RESET_KEY.replace('{id}', Base64.encodeURL(email)),
        );
    },
    getByResetKey: (resetKey) => {
        return backendAxiosClient.get(
            URL_BACK_USER_GET_BY_RESET_KEY.replace('{resetKey}', resetKey),
        );
    },
    getPage: (page) => {
        return backendAxiosClient.get(URL_BACK_USER_COLLECTION, {
            params: { page: page },
        });
    },
    getSearch: (search, page) => {
        return backendAxiosClient.get(URL_BACK_USER_COLLECTION, {
            params: { page: page, search: search },
        });
    },
    deactivate: (email = null) => {
        if (email === null) {
            return backendAxiosClient.post(
                URL_BACK_USER_DEACTIVATION.replace(
                    '{id}',
                    Buffer.from(getPayloadToken().username).toString('base64'),
                ),
            );
        } else {
            return backendAxiosClient.post(
                URL_BACK_USER_DEACTIVATION.replace(
                    '{id}',
                    Buffer.from(email).toString('base64'),
                ),
            );
        }
    },
    getConnected: (mail) => {
        return backendAxiosClient.get(
            URL_BACK_USER_CURRENT.replace(
                '{email}',
                Buffer.from(mail).toString('base64'),
            ),
        );
    },
    reactivationRequest: (email) => {
        return backendAxiosClient.post(
            URL_BACK_USER_REACTIVATION_REQUEST.replace('{id}', Base64.encodeURL(email)),
        );
    },
};
