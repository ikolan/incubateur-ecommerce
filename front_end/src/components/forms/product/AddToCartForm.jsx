import { Field, Form, Formik } from 'formik';
import React from 'react';

import { schemaFormAddToCart } from '../../../constants/yup-schemas/schemaFormAddToCart';
import Input from '../../utils/Input';

export const AddToCartForm = ({ initialValues, onSubmit, isDisabled = false }) => {
    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={initialValues}
            validationSchema={schemaFormAddToCart}
        >
            <Form className="flex space-x-3">
                <Field
                    className="justify-self-end border-2 rounded-md text-xl font-semibold lg:h-12 lg:w-12 xl:w-20 xl:h-20 "
                    name="quantity"
                    type="text"
                    component={Input}
                />
                <button
                    type="submit"
                    className="justify-self-end text-white border rounded-md bg-orange-600 disabled:bg-orange-300 lg:h-12 xl:h-20 lg:w-46 xl:w-80 lg:text-xl xl:text-2xl"
                    disabled={isDisabled}
                >
                    {isDisabled ? 'Produit non disponible' : 'Ajouter au panier'}
                </button>
            </Form>
        </Formik>
    );
};
