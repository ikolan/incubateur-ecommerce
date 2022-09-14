import React, { useEffect, useState } from 'react';
import { useHistory, useParams } from 'react-router-dom';

import { backendApi } from '../../api/backend/backendApi';
import ProductDetail from '../../components/layouts/products/ProductDetail';
import { URL_NOT_FOUND } from '../../constants/urls';

const ProductDetailView = () => {
    const { reference } = useParams();
    const history = useHistory();
    const [product, setproduct] = useState({});
    useEffect(() => {
        backendApi.product.getProduct(reference).then((response) => {
            if (response.data.isDeleted) {
                history.push(URL_NOT_FOUND);
            }
            setproduct(response.data);
        });
    }, []);
    if (Object.entries(product).length !== 0) {
        return (
            <div className="w-full">
                <ProductDetail product={product} />
            </div>
        );
    }
    return <div></div>;
};

export default ProductDetailView;
