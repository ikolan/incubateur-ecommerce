import React, { useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { initialFormValues } from '../../../constants/initialFormValues';
import { CenteredDialogPanel } from '../../containers/CenteredDialogPanel';
import { RegisterForm } from '../../forms/user/RegisterForm';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';

/**
 * Component for the user registration form.
 *
 * @author Nicolas Benoit
 */
const Register = () => {
    let [isSubmitting, setIsSubmitting] = useState(false);
    let [errorMessage, setErrorMessage] = useState('');
    let [successMessage, setSuccessMessage] = useState('');
    let [lastValuesSubmitted, setLastValuesSubmitted] = useState(
        initialFormValues.register,
    );

    const onRegistrationSuccess = () => {
        setIsSubmitting(false);
        setSuccessMessage('Votre compte à bien été créer.');
        setLastValuesSubmitted(initialFormValues.register);
    };

    const onRegistrationError = ({ response }) => {
        setIsSubmitting(false);
        if (
            response.status === 422 &&
            response.data['@type'] === 'ConstraintViolationList' &&
            response.data.violations.filter(
                (value) => value.code === 'AlreadyRegisteredEmail',
            ).length > 0
        ) {
            setErrorMessage('Il existe déja un compte relié à cette adresse email.');
        }
    };

    const handleRegistration = (values) => {
        setIsSubmitting(true);
        setErrorMessage('');
        setSuccessMessage('');
        setLastValuesSubmitted(values);
        backendApi.user
            .post({
                firstName: values.firstName,
                lastName: values.lastName,
                phone: values.phone,
                birthDate: values.birthDate,
                email: values.email,
                password: values.password,
            })
            .then(onRegistrationSuccess)
            .catch(onRegistrationError);
    };

    const processMessage = () => {
        if (errorMessage !== '') {
            return <MessageBox type="error">{errorMessage}</MessageBox>;
        } else if (successMessage !== '') {
            return <MessageBox type="success">{successMessage}</MessageBox>;
        }
    };

    return (
        <CenteredDialogPanel className="flex justify-center items-center p-10 max-w-[800px] w-full md:w-[80vw] min-h-[100vh] md:min-h-[60vh] rounded-none md:rounded">
            <div className="w-full max-w-[600px]">
                <h2 className="m-3 text-center text-3xl font-extrabold text-white">
                    Créer votre compte
                </h2>
                <hr className="border-primary" />
                {processMessage()}
                {isSubmitting ? (
                    <div>
                        <Spinner
                            className="mt-10"
                            legend="Création de votre compte en cours..."
                            lightMode
                        />
                    </div>
                ) : (
                    <RegisterForm
                        initialValues={lastValuesSubmitted}
                        onSubmit={handleRegistration}
                    />
                )}
            </div>
        </CenteredDialogPanel>
    );
};

export default Register;
