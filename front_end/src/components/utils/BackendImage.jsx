import React from 'react';

import { URL_BACK_IMAGE_ITEM_DATA } from '../../constants/urlsBack';

export const BackendImage = ({ id, alt, ...rest }) => {
    return (
        <img
            src={`${import.meta.env.VITE_BACKEND_URL}${URL_BACK_IMAGE_ITEM_DATA.replace(
                '{id}',
                id,
            )}`}
            alt={alt}
            {...rest}
        />
    );
};
