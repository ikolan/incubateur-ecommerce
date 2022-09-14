import { Field, Form, Formik } from 'formik';
import React, { useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_LOGIN } from '../../../constants/urls';
import { schemaFormValidateOrder } from '../../../constants/yup-schemas/schemaFormValidateOrder';
import { isAuthenticated } from '../../../services/accountServices';
import { AddressPickerInput } from '../../utils/AddressPickerInput';
import { Button } from '../../utils/Button';
import { Price } from '../../utils/Price';
import { Spinner } from '../../utils/Spinner';
import GetUser from '../user/GetUser';

const CartSideValidationForm = ({ onSubmit, inProgress }) => {
    return (
        <Formik
            initialValues={{ address: '' }}
            onSubmit={onSubmit}
            validationSchema={schemaFormValidateOrder}
        >
            <Form>
                <label htmlFor="address" className="mb-2">
                    Veuillez séléctionez une adresse de livraison
                </label>
                <Field name="address" component={AddressPickerInput} className="mt-2" />
                {inProgress ? (
                    <Spinner legend="Création de la commande..." mode="line" />
                ) : (
                    <Button type="submit" className="w-full mt-4">
                        Valider le panier pour créer votre commande
                    </Button>
                )}
            </Form>
        </Formik>
    );
};

/**
 * @author NemesisMKII
 */
const CartSide = ({ totalPrice, lineCarts }) => {
    const [orderInProgress, setOrderInProgress] = useState(false);
    const user = GetUser();

    const handleFormSubmit = (values) => {
        setOrderInProgress(true);
        let order = {
            price: totalPrice,
            isReturned: false,
            address: `/api/addresses/${values.address}`,
            items: lineCarts.map((line) => {
                return {
                    product: `/api/products/${line.product.id}`,
                    quantity: line.lineCart.quantity,
                    unitPrice: line.product.price,
                };
            }),
        };
        backendApi.order.post(order).then((response) => {
            window.location.href = response.data.link;
        });
    };

    const renderValidationForm = () => {
        const userIsActivated = user !== undefined && user.data.isActivated;
        const haveLineCarts = lineCarts.length > 0;

        if (isAuthenticated() && userIsActivated && haveLineCarts) {
            return (
                <CartSideValidationForm
                    onSubmit={handleFormSubmit}
                    inProgress={orderInProgress}
                />
            );
        }

        if (!isAuthenticated()) {
            return (
                <Button className="w-full" link={URL_LOGIN}>
                    Se connecter pour valider le panier
                </Button>
            );
        }

        if (isAuthenticated() && !userIsActivated) {
            return (
                <div className="border rounded text-center font-bold p-2">
                    <p>Votre compte n'est pas activé.</p>
                </div>
            );
        }
    };

    return (
        <div className="lg:w-1/5 2xl:w-2/5 h-3/4 space-y-5">
            <div className="bg-white shadow-cartProduct rounded p-5">
                <p className="text-secondary text-2xl">Récapitulatif</p>
                <div className="flex justify-center items-center mt-5">
                    <Price>{totalPrice ? totalPrice : 0}</Price>
                </div>
            </div>
            {renderValidationForm()}
        </div>
    );
};

export default CartSide;
