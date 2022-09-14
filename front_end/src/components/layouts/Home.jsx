import React from 'react';

import { URL_PRODUCTS_HOME } from '../../constants/urls';
import ProductPageDescription from '../containers/ProductPageDescription';
import { Button } from '../utils/Button';
import logo from './../../assets/images/logo.png';
import FrontProducts from './products/FrontProducts';

export const Home = () => {
    return (
        <>
            <div className="h-[calc(100vh-300px)] bg-secondary flex flex-col justify-center items-center">
                <h1 className="text-white text-8xl mb-28">Bienvenue chez</h1>
                <img src={logo} alt="Craft Computing logo" width="600px" />
            </div>
            <div className="m-24">
                <ProductPageDescription />
                <FrontProducts />
                <div className="flex justify-center mt-24">
                    <Button
                        link={URL_PRODUCTS_HOME}
                        className="w-full max-w-[900px] text-2xl"
                    >
                        Voir tous nos produits
                    </Button>
                </div>
            </div>
        </>
    );
};
