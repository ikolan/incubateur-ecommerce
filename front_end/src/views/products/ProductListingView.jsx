import React from 'react';

import FrontProducts from '../../components/layouts/products/FrontProducts';
import ProductListing from '../../components/layouts/products/ProductListing';

const ProductListingView = () => {
    return (
        <div className="mt-3 p-8 space-y-20">
            <FrontProducts />
            <ProductListing />
        </div>
    );
};

export default ProductListingView;
