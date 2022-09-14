import React from 'react';

import Cart from '../../components/layouts/cart/Cart';

const CartView = () => {
    return (
        <div>
            <div className="p-8 space-x-5 h-screen">
                <Cart />
            </div>
        </div>
    );
};

export default CartView;
