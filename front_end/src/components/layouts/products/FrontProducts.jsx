import { Base64 } from 'js-base64';
import React from 'react';
import { useEffect } from 'react';
import { useState } from 'react';
import { Link } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import cartIcon from '../../../assets/icons/cart.svg';
import { URL_PRODUCTS_DETAIL } from '../../../constants/urls';
import { accountEmail, isAuthenticated } from '../../../services/accountServices';
import { addToCart } from '../../../services/lineCartServices';
import { BackendImage } from '../../utils/BackendImage';
import { NavbarIconButton } from '../../utils/NavbarIconButton';
import { Price } from '../../utils/Price';
import { ProductNoPicture } from '../../utils/ProductNoPicture';

/**
 * @author NemesisMKII
 */
const FrontProducts = () => {
    const [frontProducts, setFrontProducts] = useState([]);

    useEffect(() => {
        backendApi.product.getFrontProducts().then((data) => {
            setFrontProducts(data.data['hydra:member']);
            console.log(data.data['hydra:member']);
        });
    }, []);

    return (
        <div className="w-full">
            <h3 className="text-blue-800 uppercase text-center">Nos produits phares</h3>
            <div className="mt-8 p-4 text-2xl flex flex-wrap justify-center items-center">
                {frontProducts.map((product) => (
                    <div
                        key={product['@id']}
                        className="min-w-[250px] w-1/6 h-[450px] bg-white drop-shadow-productBox p-2 m-4 lg:w-[25%] 2xl:w-1/6"
                    >
                        <Link
                            to={URL_PRODUCTS_DETAIL.replace(
                                ':reference',
                                product.reference,
                            )}
                        >
                            <div className="h-[90%] space-y-5">
                                <div className="w-full h-1/2">
                                    {product.image === undefined ? (
                                        <ProductNoPicture />
                                    ) : (
                                        <div className="h-full p-1 flex items-center">
                                            <BackendImage
                                                className="rounded max-h-full"
                                                id={product.image.id}
                                                alt={product.image.name}
                                            />
                                        </div>
                                    )}
                                </div>
                                <p className="text-2xl font-bold text-center">
                                    {product.name}
                                </p>
                                <p className="text-xl text-green-500 font-bold text-center">
                                    {product.stock > 0 ? 'EN STOCK.' : 'INDISPONIBLE'}
                                </p>
                            </div>
                        </Link>
                        <div className="flex items-end justify-between">
                            <Price>{product.price}</Price>
                            <div className="bg-orange-500 z-50 flex justify-center rounded w-[50px] h-[50px] p-1">
                                <NavbarIconButton
                                    icon={cartIcon}
                                    onClick={() => {
                                        if (isAuthenticated()) {
                                            backendApi.lineCarts.addToCart({
                                                quantity: 1,
                                                product: product.reference,
                                                cartUser: Base64.encodeURL(
                                                    accountEmail(),
                                                ),
                                                add: true,
                                            });
                                        } else {
                                            addToCart(product.reference, 1);
                                        }
                                    }}
                                />
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default FrontProducts;
