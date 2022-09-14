import React from 'react';

import { makePriceObject } from '../../services/priceService';

export const Price = ({ children, className, ...rest }) => {
    const price = makePriceObject(children);
    return (
        <p className={'price text-4xl lg:text-2xl xl:text-5xl font-semibold ' + className} {...rest}>
            {price.value}
            {price.currency}
            {price.cents !== '00' ? (
                <span className="lg:text-xl xl:text-3xl">{price.cents}</span>
            ) : (
                <></>
            )}
        </p>
    );
};
