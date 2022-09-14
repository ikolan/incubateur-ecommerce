import { Field, Form, Formik } from 'formik';
import React from 'react';

import Input from '../../utils/Input';
import { schemaFormChangePassword } from './../../../constants/yup-schemas/schemaFormChangePassword';

/**
 * @author NemesisMKII
 */
export const ChangePasswordForm = ({ onSubmit, initialValues }) => {
    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={initialValues}
            validationSchema={schemaFormChangePassword}
        >
            <Form className="mt-3 h-full text-center">
                <div>
                    <label htmlFor="oldPassword">Ancien mot de passe:</label>
                    <Field
                        className="rounded border w-64 mx-auto mt-1"
                        name="oldPassword"
                        type="password"
                        placeholder="Ancien mot de passe"
                        component={Input}
                    />
                </div>
                <div className="mt-4">
                    <label htmlFor="newPassword">Nouveau mot de passe:</label>
                    <Field
                        className="rounded border w-64 mx-auto mt-1"
                        name="newPassword"
                        type="password"
                        placeholder="Mot de passe"
                        component={Input}
                    />
                </div>
                <div className="mt-4">
                    <label htmlFor="repeatNewPassword">
                        Répéter nouveau mot de passe:
                    </label>
                    <Field
                        className="rounded border w-64 mx-auto mt-1"
                        name="repeatNewPassword"
                        type="password"
                        placeholder="Confirmation du mot de passe"
                        component={Input}
                    />
                </div>
                <button
                    className="w-32 h-14 text-2xl btn btn-primary block mx-auto mt-8"
                    type="submit"
                >
                    Valider
                </button>
            </Form>
        </Formik>
    );
};
