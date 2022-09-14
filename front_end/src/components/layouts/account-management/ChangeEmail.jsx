import { Field, Form, Formik } from 'formik';
import React, { useState } from 'react';
import { useDispatch } from 'react-redux';

import { backendApi } from '../../../api/backend/backendApi';
import { schemaFormChangeEmail } from '../../../constants/yup-schemas/schemaFormChangeEmail';
import { signIn } from '../../../redux/authenticationSlice';
import { accountEmail } from '../../../services/accountServices';
import Input from '../../utils/Input';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';

const FormEmail = ({ onSubmit, initialValues }) => {
    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={initialValues}
            validationSchema={schemaFormChangeEmail}
        >
            <Form className="mt-3 h-full text-center">
                <div>
                    <label htmlFor="mail">Nouvelle adresse mail:</label>
                    <Field
                        className="rounded border w-64 mx-auto mt-1"
                        name="mail"
                        type="mail"
                        placeholder="Nouvelle adresse mail"
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

const initialFormValues = {
    mail: '',
};

const ChangeEmail = () => {
    const dispatch = useDispatch();

    let [isSubmitting, setIsSubmitting] = useState(false);
    let [lastValuesSubmitted, setLastValuesSubmitted] = useState(initialFormValues);
    let [successMessage, setSuccessMessage] = useState('');
    let [errorMessage, setErrorMessage] = useState('');

    const onActionSuccess = (response) => {
        setIsSubmitting(false);
        setSuccessMessage('Email modifié !');
        setLastValuesSubmitted(initialFormValues);
        dispatch(signIn(response.data.token));
    };

    const onActionError = () => {
        setIsSubmitting(false);
        setErrorMessage('une erreur a eu lieu');
    };

    const processMessage = () => {
        if (errorMessage !== '') {
            return <MessageBox type="error">{errorMessage}</MessageBox>;
        } else if (successMessage !== '') {
            return <MessageBox type="success">{successMessage}</MessageBox>;
        }
    };

    return (
        <div className="w-2/3 mx-16">
            {processMessage()}
            {isSubmitting ? (
                <Spinner legend="Changement en cours..." />
            ) : (
                <FormEmail
                    initialValues={lastValuesSubmitted}
                    onSubmit={(formInput) => {
                        setIsSubmitting(true);
                        setErrorMessage('');
                        setSuccessMessage('');
                        setLastValuesSubmitted(formInput);
                        backendApi.user
                            .patchEmail(accountEmail(), {
                                email: formInput.mail,
                            })
                            .then(onActionSuccess)
                            .catch(onActionError);
                    }}
                />
            )}
        </div>
    );
};

export default ChangeEmail;
