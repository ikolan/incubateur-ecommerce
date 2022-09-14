import React, { useState } from 'react';
import { useDispatch } from 'react-redux';

import { backendApi } from '../../../api/backend/backendApi';
import { signIn } from '../../../redux/authenticationSlice';
import { accountEmail } from '../../../services/accountServices';
import { ChangePasswordForm } from '../../forms/user/ChangePasswordForm';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';
import { initialFormValues } from './../../../constants/initialFormValues';

const ChangePassword = () => {
    let [isSubmitting, setIsSubmitting] = useState(false);
    let [lastValuesSubmitted, setLastValuesSubmitted] = useState(
        initialFormValues.changePassword,
    );
    let [successMessage, setSuccessMessage] = useState('');
    let [errorMessage, setErrorMessage] = useState('');
    const dispatch = useDispatch();

    const onActionSuccess = (response) => {
        setIsSubmitting(false);
        setSuccessMessage('Mot de passe modifié !');
        setLastValuesSubmitted(initialFormValues.changePassword);
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
                <ChangePasswordForm
                    initialValues={lastValuesSubmitted}
                    onSubmit={(formInput) => {
                        setIsSubmitting(true);
                        setErrorMessage('');
                        setSuccessMessage('');
                        setLastValuesSubmitted(formInput);
                        backendApi.user
                            .patchEmail(accountEmail(), {
                                oldpassword: formInput.oldPassword,
                                newpassword: formInput.newPassword,
                            })
                            .then(onActionSuccess)
                            .catch(onActionError);
                    }}
                />
            )}
        </div>
    );
};

export default ChangePassword;
