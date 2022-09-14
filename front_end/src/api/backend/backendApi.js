import { addressApi } from './address';
import { categoryApi } from './category';
import { contactApi } from './contact';
import { imageApi } from './image';
import { lineCartApi } from './lineCart';
import { orderApi } from './order';
import { orderReturnApi } from './orderReturn';
import { productApi } from './product';
import { statusApi } from './status';
import { tagApi } from './tag';
import { userApi } from './user';

export const backendApi = {
    address: addressApi,
    user: userApi,
    product: productApi,
    category: categoryApi,
    contact: contactApi,
    tag: tagApi,
    lineCarts: lineCartApi,
    image: imageApi,
    order: orderApi,
    orderReturn: orderReturnApi,
    status: statusApi,
};
