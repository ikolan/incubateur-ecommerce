import { Field, Form, Formik } from 'formik';
import React from 'react';

import { Button } from '../../utils/Button';
import Input from '../../utils/Input';
import { schemaFormAddress } from './../../../constants/yup-schemas/schemaFormAddress';

/**
 * Form for adding/modify an address.
 */
export const AddressForm = ({ initialValues, onSubmit, onClose, submitButtonValue }) => {
    return (
        <Formik
            validationSchema={schemaFormAddress}
            initialValues={initialValues}
            onSubmit={onSubmit}
        >
            <Form className="m-4">
                <label htmlFor="title">Nom de l'adresse :</label>
                <Field
                    type="text"
                    name="title"
                    placeholder="(Ex.: Maison, Travail, etc...)"
                    component={Input}
                    className="rounded mb-2"
                />
                <label htmlFor="number">Rue :</label>
                <div className="flex mb-2">
                    <div className="w-32">
                        <Field
                            type="number"
                            name="number"
                            component={Input}
                            className="rounded-none rounded-l border-r-0"
                            min="1"
                        />
                    </div>
                    <div className="w-full">
                        <Field
                            type="text"
                            name="road"
                            placeholder="Nom de rue"
                            component={Input}
                            className="rounded-none rounded-r"
                        />
                    </div>
                </div>
                <label htmlFor="zipcode">Ville :</label>
                <div className="flex mb-2">
                    <div className="w-32">
                        <Field
                            type="text"
                            name="zipcode"
                            placeholder="Code postal"
                            component={Input}
                            className="rounded-none rounded-l border-r-0"
                        />
                    </div>
                    <div className="w-full">
                        <Field
                            type="text"
                            name="city"
                            placeholder="Ville"
                            component={Input}
                            className="rounded-none rounded-r"
                        />
                    </div>
                </div>
                <label htmlFor="phone">Numéro de contact :</label>
                <Field
                    type="tel"
                    name="phone"
                    component={Input}
                    className="rounded mb-4"
                />
                <button className="btn btn-primary mr-2" type="submit">
                    {submitButtonValue}
                </button>
                {onClose !== undefined ? (
                    <Button style="red" onClick={onClose}>
                        Fermer
                    </Button>
                ) : (
                    <></>
                )}
            </Form>
        </Formik>
    );
};
