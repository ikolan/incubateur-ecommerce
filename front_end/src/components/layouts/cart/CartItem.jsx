import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import downChevron from '../../../assets/icons/downChevron.svg';
import upChevron from '../../../assets/icons/upChevron.svg';
import { URL_PRODUCTS_DETAIL } from '../../../constants/urls';
import { accountEmail, isAuthenticated } from '../../../services/accountServices';
import { BackendImage } from '../../utils/BackendImage';
import { Button } from '../../utils/Button';
import { Price } from '../../utils/Price';
import { ProductNoPicture } from '../../utils/ProductNoPicture';
import ProductList from '../products/ProductList';

/**
 * @author NemesisMKII
 */
const CartItem = (props) => {
    const [lineCart, setLineCart] = useState(props.lineCart.lineCart);
    const product = props.lineCart.product;
    const [change, setChange] = useState(false);

    const changeQuantity = (quantity) => {
        if (quantity <= 0) {
            props.onCartDelete({
                lineCart: lineCart,
                product: product,
            });
        } else {
            setLineCart((previousState) => {
                setChange(true);
                return { ...previousState, quantity: quantity };
            });
        }
    };

    useEffect(() => {
        if (change) {
            props.onCartUpdate({
                lineCart: lineCart,
                product: product,
            });
            setChange(false);
        }
    }, [lineCart]);

    console.log(product);

    return (
        <div className="w-full h-32 bg-white shadow-cartProduct rounded flex justify-between items-center p-1">
            <div className="w-32 h-full flex items-center justify-center">
                {product.image === undefined ? (
                    <ProductNoPicture className="rounded min-w-[100px] text-center" />
                ) : (
                    <div className="flex justify-center items-center w-full h-full">
                        <BackendImage id={product.image.id} alt={product.image.name} />
                    </div>
                )}
            </div>
            <div>
                <p className="lg:text-lg xl:text-xl 2xl:text-2xl font-medium">{product.name}</p>
                <p>
                    Disponibilité:{' '}
                    {product.stock > 0 ? (
                        <span className="text-green-500 font-bold">INDISPONIBLE</span>
                    ) : (
                        <span className="text-red-500 font-bold">INDISPONIBLE</span>
                    )}
                </p>
            </div>
            <div className="border-2 border-black rounded flex items-center lg:w-12 lg:h-10 xl:w-14 xl:h-12 p-1">
                <p className="text-2xl font-medium w-3/4">{lineCart.quantity}</p>
                <div className="w-2/4 h-full flex flex-col justify-center">
                    <button
                        className="font-bold text-2xl"
                        onClick={() => changeQuantity(lineCart.quantity + 1)}
                    >
                        <img src={upChevron} />
                    </button>
                    <button
                        className="font-bold text-2xl"
                        onClick={() => changeQuantity(lineCart.quantity - 1)}
                    >
                        <img src={downChevron} />
                    </button>
                </div>
            </div>
            <Price className={'text-3xl'}>{product.price}</Price>
            <button
                className="text-7xl font-thin text-gray-500"
                onClick={() => {
                    props.onCartDelete({ lineCart: lineCart, product: product });
                }}
            >
                x
            </button>
        </div>
    );

    return (
        <div className="w-full h-32 bg-white shadow-cartProduct rounded flex items-center p-1">
            <div className="w-32 h-full flex items-center justify-center">
                <p className="text-2xl">PHOTO</p>
            </div>
            <div className="w-full flex justify-between items-center">
                <div className="h-full flex flex-col justify-between">
                    <div>
                        <Link
                            to={URL_PRODUCTS_DETAIL.replace(
                                ':reference',
                                product.reference,
                            )}
                        >
                            <p className="text-xl font-bold">{product.name}</p>
                        </Link>
                        <p className="text-xl font-medium">
                            {(product.price / 100).toFixed(2)}€
                        </p>
                    </div>
                    <div className="flex items-center space-x-3">
                        <p className="text-xl font-medium">
                            Quantité: {lineCart.quantity}
                        </p>
                        <button
                            className="text-red-500 font-bold text-3xl"
                            onClick={() => changeQuantity(lineCart.quantity + 1)}
                        >
                            +
                        </button>
                        <button
                            className="text-green-500 font-bold text-3xl"
                            onClick={() => changeQuantity(lineCart.quantity - 1)}
                        >
                            -
                        </button>
                    </div>
                </div>
                <Button
                    className="h-12"
                    onClick={() => {
                        props.onCartDelete({ lineCart: lineCart, product: product });
                    }}
                >
                    Supprimer du panier
                </Button>
            </div>
        </div>
    );
};

export default CartItem;
