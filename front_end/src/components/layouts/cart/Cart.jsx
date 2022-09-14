import { current } from '@reduxjs/toolkit';
import axios from 'axios';
import { Base64 } from 'js-base64';
import debounce from 'lodash.debounce';
import React, { useEffect, useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import trash from '../../../assets/icons/trash.svg';
import { accountEmail, isAuthenticated } from '../../../services/accountServices';
import { Spinner } from '../../utils/Spinner';
import CartItem from './CartItem';
import CartSide from './CartSide';

/**
 * @author NemesisMKII
 */
const Cart = () => {
    const [lineCarts, setLineCarts] = useState([]);
    const [isLoading, setIsloading] = useState(true);
    const [totalPrice, setTotalPrice] = useState();

    useEffect(() => {
        getCart();
        if (totalPrice) {
            console.log(totalPrice, 'price');
        }
    }, []);

    useEffect(() => {
        GetTotalPrice(lineCarts);
    }, [lineCarts]);

    function getCart() {
        if (isAuthenticated()) {
            if (localStorage.getItem('cart')) {
                let cart = JSON.parse(localStorage.getItem('cart'));
                cart.forEach((cartItem) => {
                    let promise = backendApi.lineCarts.addToCart({
                        quantity: cartItem.quantity,
                        product: cartItem.reference,
                        cartUser: Base64.encodeURL(accountEmail()),
                        add: true,
                    });
                });
                localStorage.setItem('cart', '[]');
            }
            backendApi.lineCarts.getCollection(accountEmail()).then((data) => {
                setLineCarts(data.data['hydra:member']);
                setIsloading(false);
            });
        } else {
            var cart = JSON.parse(localStorage.getItem('cart')) ?? [];
            if (cart.length > 0) {
                let promises = [];
                cart.forEach((element) => {
                    const promise = backendApi.product.getProduct(element.reference);
                    promises.push(promise);
                });
                var items = [];
                axios.all(promises).then(
                    axios.spread((...responses) => {
                        responses.forEach((response) => {
                            cart.forEach((element) => {
                                if (element.reference == response.data.reference) {
                                    items.push({
                                        lineCart: {
                                            quantity: element.quantity,
                                        },
                                        product: response.data,
                                    });
                                }
                            });
                        });
                        setLineCarts(items);
                        setIsloading(false);
                    }),
                );
            } else {
                setIsloading(false);
            }
        }
    }

    function setCart(lineCart, lineCartDelete = false) {
        if (isAuthenticated()) {
            if (lineCartDelete) {
                backendApi.lineCarts.delete(lineCart.lineCart.id);
            } else {
                backendApi.lineCarts.patch(lineCart.lineCart.id, {
                    quantity: lineCart.lineCart.quantity,
                    add: true,
                });
            }
        } else {
            console.log(lineCart);
            var cart = JSON.parse(localStorage.getItem('cart')) ?? [];
            if (lineCartDelete) {
                cart = cart.filter((current) => {
                    return current.reference !== lineCart.product.reference;
                });
            } else {
                cart.forEach((cartItem) => {
                    if (cartItem.reference == lineCart.product.reference) {
                        cartItem.quantity = lineCart.lineCart.quantity;
                    }
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
        }
    }

    const updateLineCarts = (lineCart) => {
        setLineCarts((current) =>
            current.map((obj) => {
                if (obj.product.reference == lineCart.product.reference) {
                    return { ...lineCart };
                }
                return obj;
            }),
        );
        setCart(lineCart);
    };

    const deleteLineCarts = (lineCart) => {
        setLineCarts((current) =>
            current.filter((obj) => {
                return obj.product.reference !== lineCart.product.reference;
            }),
        );
        setCart(lineCart, true);
    };

    const deleteCart = () => {
        setLineCarts([]);
        if (isAuthenticated()) {
            backendApi.lineCarts.deleteAll(accountEmail());
        }
    };

    const GetTotalPrice = (lineCarts) => {
        var cartPrice = 0;
        if (lineCarts && lineCarts.length > 0) {
            lineCarts.forEach((lineCart) => {
                cartPrice += lineCart.lineCart.quantity * lineCart.product.price;
            });
        }
        setTotalPrice(cartPrice);
    };

    return (
        <div className="p-5 mx-auto">
            <div className="w-screen h-20">
                <h5 className="text-secondary-light">MON PANIER</h5>
                <p className="text-secondary-dark mt-2">Actuellement: {lineCarts.length} article{lineCarts.length > 1 ? "s" : <></>}</p>
            </div>
            <div className="flex space-x-5">
                {isLoading ? (
                    <div className="w-4/5 h-3/4">
                        <Spinner legend={'Chargement du panier'} />
                    </div>
                ) : lineCarts && lineCarts.length > 0 ? (
                    <div className="lg:w-4/5 2xl:w-3/5 h-3/4">
                        <div className="space-y-8">
                            {lineCarts.map((lineCart) => (
                                <CartItem
                                    key={lineCart.product.reference}
                                    lineCart={lineCart}
                                    onCartUpdate={debounce((cart) => {
                                        updateLineCarts(cart);
                                    }, 500)}
                                    onCartDelete={(cart) => {
                                        deleteLineCarts(cart);
                                    }}
                                />
                            ))}
                        </div>
                        <button
                            className="flex float-right mt-3 items-center space-x-1"
                            onClick={() => {
                                deleteCart();
                            }}
                        >
                            <img src={trash} className="w-4 h-4" />
                            <p className="font-medium">Vider le panier</p>
                        </button>
                    </div>
                ) : (
                    <div className="w-4/5 h-3/4">
                        <p className="text-center text-3xl font-medium">
                            Votre panier est vide.
                        </p>
                    </div>
                )}
                <CartSide totalPrice={totalPrice} lineCarts={lineCarts} />
            </div>
        </div>
    );

    if (isLoading) {
        return (
            <div className="w-3/5 h-3/4 border-2 p-1 space-y-1">
                <Spinner legend={'Chargement du panier'} />
            </div>
        );
    } else {
        if (lineCarts && lineCarts.length > 0) {
            return (
                <div className="w-3/5 h-3/4 border-2 p-1 space-y-1">
                    {lineCarts.map((lineCart) => (
                        <CartItem
                            key={lineCart.product.reference}
                            lineCart={lineCart}
                            onCartUpdate={debounce((cart) => {
                                updateLineCarts(cart);
                            }, 500)}
                            onCartDelete={(cart) => {
                                deleteLineCarts(cart);
                            }}
                        />
                    ))}
                </div>
            );
        } else {
            return (
                <div className="w-3/5 h-3/4 border-2 p-1 space-y-1">
                    Votre panier est vide.
                </div>
            );
        }
    }
};

export default Cart;
