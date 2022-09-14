import { Field, Form, Formik } from 'formik';
import React from 'react';

import { initialFormValues } from '../../../constants/initialFormValues';
import { Button } from '../../utils/Button';
import Input from '../../utils/Input';
import { schemaFormForgotPasswordStepOne } from './../../../constants/yup-schemas/schemaFormForgotPasswordStepOne';

export const ForgetPasswordStepOneForm = ({ onSubmit }) => {
    return (
        <Formik
            initialValues={initialFormValues.forgotPasswordStepOne}
            validationSchema={schemaFormForgotPasswordStepOne}
            onSubmit={onSubmit}
        >
            <Form>
                <label htmlFor="email" className="text-white">
                    Adresse email :
                </label>
                <Field name="email" type="text" component={Input} />
                <Button type="submit" style="primary" className="mt-4">
                    Envoyer
                </Button>
            </Form>
        </Formik>
    );
};
