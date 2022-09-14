import React from 'react';
import { useHistory } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import { initialFormValues } from '../../../constants/initialFormValues';
import { URL_ADMIN_PRODUCTS } from '../../../constants/urls';
import { AdminProductForm } from '../../forms/admin/AdminProductForm';

const productToInitialValues = (product) => {
    return {
        name: product.name,
        reference: product.reference,
        price: product.price,
        tax: product.tax,
        stock: product.stock,
        category: product.category.id,
        tags: product.tags.map((tag) => tag.id),
        description: product.description,
        detailedDescription: product.detailedDescription,
        frontPage: product.frontPage,
        image: product.image === undefined ? '' : product.image,
    };
};

export const AdminEditProduct = ({ product = null }) => {
    const history = useHistory();

    return (
        <div className="m-3">
            <AdminProductForm
                onSubmit={(values) => {
                    const afterRequest = () => history.push(URL_ADMIN_PRODUCTS);
                    values.tags = values.tags.map((tagId) => `/api/tags/${tagId}`);
                    values.category = `/api/categories/${values.category}`;
                    values.image =
                        values.image === undefined ? null : values.image['@id'];
                    product === null
                        ? backendApi.product.post(values).then(afterRequest)
                        : backendApi.product.patch(product.id, values).then(afterRequest);
                }}
                submitButtonChildren={
                    product === null ? 'Ajouter le produit' : 'Modifier le produit'
                }
                initialValues={
                    product === null
                        ? initialFormValues.adminProduct
                        : productToInitialValues(product)
                }
            />
        </div>
    );
};
