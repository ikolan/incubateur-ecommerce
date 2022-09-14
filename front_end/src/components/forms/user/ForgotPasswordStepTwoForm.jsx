import { Field, Form, Formik } from 'formik';
import React from 'react';

import { initialFormValues } from '../../../constants/initialFormValues';
import Input from '../../utils/Input';
import { schemaFormForgetPasswordStepTwo } from './../../../constants/yup-schemas/schemaFormForgotPasswordStepTwo';

export const ForgotPasswordStepTwoForm = ({ onSubmit }) => {
    return (
        <Formik
            initialValues={initialFormValues.forgotPasswordStepTwo}
            validationSchema={schemaFormForgetPasswordStepTwo}
            onSubmit={onSubmit}
        >
            <Form>
                <div>
                    <Field
                        className="rounded-none rounded-t border-b-0 focus:border"
                        name="password"
                        type="password"
                        placeholder="Nouveau mot de passe"
                        component={Input}
                        followColumn={true}
                    />
                </div>
                <div>
                    <Field
                        className="rounded-none rounded-b focus:border"
                        name="passwordConfirmation"
                        type="password"
                        placeholder="Confirmation du nouveau mot de passe"
                        component={Input}
                    />
                </div>
                <button type="submit" className="mt-4 btn btn-primary">
                    Changer le mot de passe
                </button>
            </Form>
        </Formik>
    );
};
