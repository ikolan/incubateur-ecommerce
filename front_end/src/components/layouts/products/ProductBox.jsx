import React from 'react';
import { Link } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import cartIcon from '../../../assets/icons/cart.svg';
import { URL_PRODUCTS_DETAIL } from '../../../constants/urls';
import { BackendImage } from '../../utils/BackendImage';
import { NavbarIconButton } from '../../utils/NavbarIconButton';
import { Price } from '../../utils/Price';
import { ProductNoPicture } from '../../utils/ProductNoPicture';

/**
 * @author NemesisMKII
 */
const ProductBox = ({ product }) => {
    return (
        <div>
            <Link to={URL_PRODUCTS_DETAIL.replace(':reference', product.reference)}>
                <div
                    key={product.id}
                    className="w-full lg:h-56 xl:h-80 bg-white drop-shadow-productBox flex justify-between space-x-2 rounded"
                >
                    <div className="w-1/4 h-full flex items-center justify-center rounded-l">
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
                    <div className="w-3/4 h-full">
                        <div className="w-full h-1/5 flex justify-between p-2">
                            <p className='font-bold lg:text-2xl xl:text-3xl'>{product.name}</p>
                            <Price className={'lg:text-3xl'}>{product.price}</Price>
                        </div>
                        <div className="w-full h-3/5 flex items-center justify-center">
                            <ul className='w-[75%] h-[50%] flex flex-col flex-wrap items-between'>
                                {product.tags.map((tag) => (
                                    <li key={tag.label} className="lg:text-lg  xl:text-xl">
                                        - {tag.label}
                                    </li>
                                ))}
                            </ul>
                        </div>
                        <div className="w-full h-1/5 flex justify-between items-center px-3">
                            <p className="lg:text-xl xl:text-2xl">
                                DISPONIBILITE:{' '}
                                {product.stock > 0 ? (
                                    <span className="text-green-800 font-medium">
                                        EN STOCK
                                    </span>
                                ) : (
                                    <span className="text-red-800 font-medium">
                                        INDISPONIBLE
                                    </span>
                                )}
                            </p>
                            {/* <div className="bg-orange-500 z-50">
                                <NavbarIconButton
                                    icon={cartIcon} 
                                    onClick={() => {
                                        if (isAuthenticated()) {
                                            backendApi.lineCarts.addToCart({
                                                quantity: 1,
                                                product: product.reference,
                                                cartUser: Base64.encodeURL(accountEmail()),
                                                add: true
                                            })
                                            .then(onSuccess)
                                        } else {
                                            addToCart(product.reference, 1)
                                        }
                                    }}
                                />
                            </div> */}
                        </div>
                    </div>
                </div>
            </Link>
        </div>
    );
};

export default ProductBox;
