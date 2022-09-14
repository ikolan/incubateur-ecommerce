import { Base64 } from 'js-base64';
import React, { useEffect, useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { initialFormValues } from '../../../constants/initialFormValues';
import { accountEmail, isAuthenticated } from '../../../services/accountServices';
import { addToCart } from '../../../services/lineCartServices';
import { AddToCartForm } from '../../forms/product/AddToCartForm';
import { BackendImage } from '../../utils/BackendImage';
import MessageBox from '../../utils/MessageBox';
import { Price } from '../../utils/Price';
import { ProductNoPicture } from '../../utils/ProductNoPicture';

const ProductDetail = (props) => {
    if (props.product) {
        var product = props.product;
    }
    const [addedtoCart, setAddedToCart] = useState(false);
    const [messagetimeout, setmessagetimeout] = useState(null);
    let initialValues = initialFormValues.addToCart;

    const onSuccess = () => {
        setAddedToCart(true);
        setmessagetimeout(
            setTimeout(() => {
                setAddedToCart(false);
            }, 2000),
        );
    };

    useEffect(() => {
        return () => {
            clearTimeout();
        };
    }, []);

    return (
        <div className="w-4/5 mt-16 mx-auto space-y-20 mt-5">
            <div className="w-full h-28 shadow-productDetails rounded bg-white flex items-center">
                <div className="mx-8 space-y-2">
                    <p className="text-3xl font-semibold text-blue-800">{product.name}</p>
                    <div className="h-0.5 w-1/2 bg-red-500" />
                </div>
            </div>
            {addedtoCart ? (
                <MessageBox type="success">Produit ajouté au panier !</MessageBox>
            ) : (
                ''
            )}
            <div className="w-full shadow-productDetails drop-shadow-productDetails rounded bg-white flex flex-col space-y-3 p-4">
                <div className="flex justify-between items-stretch h-full w-full space-x-4">
                    <div>
                        {product.image === undefined ? (
                            <ProductNoPicture className="rounded min-w-[300px]" />
                        ) : (
                            <div className="flex justify-center items-center w-full h-full">
                                <BackendImage
                                    id={product.image.id}
                                    alt={product.image.name}
                                />
                            </div>
                        )}
                    </div>
                    <div className="flex flex-col justify-between w-4/6 space-y-4">
                        <div>
                            <p className="text-xl">{product.description}</p>
                        </div>
                        <p className="text-xl">
                            Disponibilité:
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
                        <div className="flex justify-between items-center">
                            <Price className="self-end">{product.price}</Price>
                            <div>
                                <AddToCartForm
                                    initialValues={initialValues}
                                    onSubmit={(formInput) => {
                                        if (isAuthenticated()) {
                                            backendApi.lineCarts
                                                .addToCart({
                                                    quantity: formInput.quantity,
                                                    product: product.reference,
                                                    cartUser: Base64.encodeURL(
                                                        accountEmail(),
                                                    ),
                                                    add: true,
                                                })
                                                .then(onSuccess);
                                        } else {
                                            addToCart(
                                                product.reference,
                                                parseFloat(formInput.quantity),
                                            );
                                        }
                                    }}
                                    isDisabled={product.stock <= 0 ? true : false}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div className="w-full space-y-8 px-8">
                <div className="w-1/3 space-y-2">
                    <p className="text-3xl font-semibold text-blue-800">
                        DESCRIPTIF DETAILLE
                    </p>
                    <div className="h-0.5 w-1/2 bg-red-500" />
                </div>
                <div className="w-full p-4">
                    <div
                        className="text-2xl"
                        dangerouslySetInnerHTML={{
                            __html: product.detailedDescription,
                        }}
                    ></div>
                </div>
            </div>
            {/* <div className="w-full flex flex-col items-center">
                <h1>{product.name}</h1>
                <div className="w-1/2 h-96 mt-12 flex justify-center items-center border-2 border-black">
                    <p>PHOTO</p>
                </div>
                <p className="text-xl ">{product.description}</p>
                <p>{product.detailedDescription}</p>
                <p className="text-2xl font-medium">{product.price}€</p>
                <button className="btn bg-orange-300">Ajouter au panier</button>
                {product.stock > 0 ? (
                    <p className="text-lime-600 font-medium text-2xl">EN STOCK</p>
                ) : (
                    ''
                )}
            </div> */}
        </div>
    );
};

export default ProductDetail;
