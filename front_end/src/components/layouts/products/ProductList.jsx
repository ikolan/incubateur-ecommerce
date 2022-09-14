import React from 'react';
import { useEffect } from 'react';
import { useState } from 'react';

import { NavButton } from '../../utils/NavButton';
import { Spinner } from '../../utils/Spinner';
import ProductBox from './ProductBox';

/**
 * @author NemesisMKII
 */
const ProductList = (props) => {
    const [isLoading, setIsloading] = useState(true);

    useEffect(() => {
        if (props.products) {
            setIsloading(false);
        }
    }, [props.products]);

    if (isLoading) {
        return (
            <div className="w-3/5 h-3/4 border-2 p-1 space-y-1">
                <Spinner legend={'Chargement de la liste des produits...'} />
            </div>
        );
    } else {
        if (props.products && props.products['hydra:member'].length > 0) {
            return (
                <div className="w-4/6 space-y-8">
                    {props.products['hydra:member'].map((product) => (
                        <ProductBox key={product.reference} product={product} />
                    ))}
                    <div className="flex justify-center space-x-3">
                        {Object.keys(props.pages).map((page) => (
                            <NavButton
                                key={page}
                                page={{ type: page, url: props.pages[page] }}
                                onPageChange={(page) => {
                                    props.onPageChange(page.url);
                                }}
                            />
                        ))}
                    </div>
                </div>
            );
        } else {
            return (
                <div className="w-4/6 space-y-8">
                    <div className="w-full h-32 border-2 flex p-1 justify-center">
                        <p className="text-3xl font-bold">
                            Aucun produit ne correspond à la recherche.
                        </p>
                    </div>
                </div>
            );
        }
    }
};

export default ProductList;
