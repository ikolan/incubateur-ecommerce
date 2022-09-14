import { Field, Form, Formik } from 'formik';
import React, { useState } from 'react';
import { useDispatch } from 'react-redux';
import { useHistory } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_USER_HOME } from '../../../constants/urls';
import { schemaFormChangeBirthDate } from '../../../constants/yup-schemas/schemaFormChangeBirthDate';
import { signIn } from '../../../redux/authenticationSlice';
import { accountEmail } from '../../../services/accountServices';
import { FormFieldErrorMessage } from '../../utils/FormFieldErrorMessage';
import Input from '../../utils/Input';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';

/**
 * @author NemesisMKII
 */
const FormBirthDate = ({ onSubmit, initialValues }) => {
    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={initialValues}
            validationSchema={schemaFormChangeBirthDate}
        >
            <Form className="mt-3 h-full text-center">
                <div>
                    <label htmlFor="birthDate">Date de naissance:</label>
                    <Field
                        className="rounded border w-64 mx-auto mt-1"
                        name="birthDate"
                        type="date"
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
    birthDate: '',
};

const ChangeBirthDate = () => {
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
                <FormBirthDate
                    initialValues={lastValuesSubmitted}
                    onSubmit={(formInput) => {
                        setIsSubmitting(true);
                        setErrorMessage('');
                        setSuccessMessage('');
                        setLastValuesSubmitted(formInput);
                        backendApi.user
                            .patchEmail(accountEmail(), {
                                birthDate: formInput.birthDate,
                            })
                            .then(onActionSuccess)
                            .catch(onActionError);
                    }}
                />
            )}
        </div>
    );
};

export default ChangeBirthDate;
