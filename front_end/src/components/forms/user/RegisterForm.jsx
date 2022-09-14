import { Field, Form, Formik } from 'formik';
import React, { useRef, useState } from 'react';

import { FormFieldErrorMessage } from '../../utils/FormFieldErrorMessage';
import Input from '../../utils/Input';
import { schemaFormRegistration } from './../../../constants/yup-schemas/schemaFormRegistration';

/**
 * The registration form.
 *
 * @author Nicolas Benoit
 */
export const RegisterForm = ({ onSubmit, initialValues }) => {
    const [passwordConfirmationOk, setPasswordConfirmationOk] = useState(false);
    const valuesRef = useRef(null);

    const verifyPasswordConfirmation = () => {
        setPasswordConfirmationOk(
            valuesRef.current !== null &&
                valuesRef.current.errors.passwordConfirmation === undefined,
        );
    };

    return (
        <Formik
            innerRef={valuesRef}
            onSubmit={onSubmit}
            initialValues={initialValues}
            validationSchema={schemaFormRegistration}
            enableReinitialize={true}
        >
            <Form className="mt-8 space-y-3">
                <div className="flex flex-row">
                    <div className="flex-1">
                        <label className="text-sm text-white" htmlFor="firstName">
                            Prénom
                        </label>
                        <Field
                            className="rounded-none rounded-l border-r-0 focus:border"
                            name="firstName"
                            type="text"
                            placeholder="Prénom"
                            component={Input}
                        />
                    </div>
                    <div className="flex-1">
                        <label className="text-sm text-white" htmlFor="lastName">
                            Nom
                        </label>
                        <Field
                            className="rounded-none rounded-r focus:border"
                            name="lastName"
                            type="text"
                            placeholder="Nom"
                            component={Input}
                        />
                    </div>
                </div>
                <div className="flex flex-row">
                    <div className="flex-1">
                        <label className="text-sm text-white" htmlFor="phone">
                            Numéro de téléphone
                        </label>
                        <Field
                            className="rounded-none rounded-l border-r-0 focus:border"
                            name="phone"
                            type="tel"
                            placeholder="Numéro de téléphone"
                            component={Input}
                        />
                    </div>
                    <div className="flex-1">
                        <label className="text-sm text-white" htmlFor="birthDate">
                            Date de naissance
                        </label>
                        <Field
                            className="rounded-none rounded-r focus:border"
                            name="birthDate"
                            type="date"
                            component={Input}
                        />
                    </div>
                </div>
                <div>
                    <div className="flex flex-row items-center">
                        <label className="flex-1 text-sm text-white" htmlFor="email">
                            Adresse Email
                        </label>
                        <div className="flex-1">
                            <Field
                                className="rounded-none rounded-t border-b-0 focus:border"
                                name="email"
                                type="email"
                                placeholder="Adresse Email"
                                component={Input}
                                followColumn
                            />
                        </div>
                    </div>
                    <div className="flex flex-row items-center">
                        <label className="flex-1 text-sm text-white" htmlFor="password">
                            Mot de passe
                        </label>
                        <div className="flex-1">
                            <Field
                                onKeyUp={() => {
                                    verifyPasswordConfirmation();
                                }}
                                className="rounded-none border-b-0 focus:border"
                                name="password"
                                type="password"
                                placeholder="Mot de passe"
                                component={Input}
                                followColumn
                            />
                        </div>
                    </div>
                    <div className="flex flex-row items-center">
                        <label
                            className="flex-1 text-sm text-white"
                            htmlFor="passwordConfirmation"
                        >
                            Confirmation du mot de passe
                        </label>
                        <div className="flex-1">
                            <Field
                                onKeyUp={() => {
                                    verifyPasswordConfirmation();
                                }}
                                className={
                                    'rounded-none rounded-b focus:border' +
                                    (passwordConfirmationOk
                                        ? ' border-green-400 bg-green-50'
                                        : '')
                                }
                                name="passwordConfirmation"
                                type="password"
                                placeholder="Confirmation du mot de passe"
                                component={Input}
                            />
                        </div>
                    </div>
                </div>
                <button className="btn btn-primary w-full" type="submit">
                    Valider
                </button>
            </Form>
        </Formik>
    );
};
