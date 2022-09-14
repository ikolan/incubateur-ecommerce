import { Field, Form, Formik } from 'formik';
import React, { useState } from 'react';
import { useDispatch } from 'react-redux';
import { useHistory } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_USER_HOME } from '../../../constants/urls';
import { schemaFormChangeName } from '../../../constants/yup-schemas/schemaFormChangeName';
import { signIn } from '../../../redux/authenticationSlice';
import { accountEmail } from '../../../services/accountServices';
import Input from '../../utils/Input';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';

const FormName = ({ onSubmit, initialValues }) => {
    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={initialValues}
            validationSchema={schemaFormChangeName}
        >
            <Form className="mt-3 h-full text-center">
                <div>
                    <label htmlFor="firstName">Prénom:</label>
                    <Field
                        className="rounded border w-64 mx-auto mt-1"
                        name="firstName"
                        type="text"
                        placeholder="Prénom"
                        component={Input}
                    />
                </div>
                <div>
                    <label htmlFor="lastName">Nom:</label>
                    <Field
                        className="rounded border w-64 mx-auto mt-1"
                        name="lastName"
                        type="text"
                        placeholder="Nom"
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
    firstName: '',
    lastName: '',
};

const ChangeName = () => {
    const history = useHistory();
    let [isSubmitting, setIsSubmitting] = useState(false);
    let [lastValuesSubmitted, setLastValuesSubmitted] = useState(initialFormValues);
    let [successMessage, setSuccessMessage] = useState('');
    let [errorMessage, setErrorMessage] = useState('');
    const dispatch = useDispatch();

    const onActionSuccess = (response) => {
        setIsSubmitting(false);
        setSuccessMessage('Modification effectuée.');
        setLastValuesSubmitted(initialFormValues);
        dispatch(signIn(response.data.token));
        history.push(URL_USER_HOME);
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
                <FormName
                    initialValues={lastValuesSubmitted}
                    onSubmit={(formInput) => {
                        setIsSubmitting(true);
                        setErrorMessage('');
                        setSuccessMessage('');
                        setLastValuesSubmitted(formInput);
                        backendApi.user
                            .patchEmail(accountEmail(), {
                                firstName: formInput.firstName,
                                lastName: formInput.lastName,
                            })
                            .then(onActionSuccess)
                            .catch(onActionError);
                    }}
                />
            )}
        </div>
    );
};

export default ChangeName;
