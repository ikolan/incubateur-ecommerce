import React, { useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom/cjs/react-router-dom.min';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_FORGOT_PASSWORD, URL_LOGIN } from '../../../constants/urls';
import { ForgotPasswordStepTwoForm } from '../../forms/user/ForgotPasswordStepTwoForm';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';

export const ForgotPasswordStepTwo = ({ resetKey }) => {
    const history = useHistory();
    const [user, setUser] = useState(null);
    const [success, setSuccess] = useState(false);
    const [isLoading, setIsLoading] = useState(false);

    const spinner = <Spinner legend="Traitements en cours..." lightMode />;

    let handleSubmit = (values) => {
        setIsLoading(true);
        backendApi.user
            .patchEmail(user.email, {
                newpassword: values.password,
                passwordResetKey: resetKey,
            })
            .then(() => {
                setSuccess(true);
                setIsLoading(false);
            });
    };

    useEffect(() => {
        backendApi.user
            .getByResetKey(resetKey)
            .then((response) => {
                setUser(response.data);
            })
            .catch(() => {
                history.push(URL_FORGOT_PASSWORD);
            });
    }, []);

    return user === null ? (
        spinner
    ) : (
        <div className="mt-5">
            {isLoading ? (
                spinner
            ) : success ? (
                <MessageBox type="success">
                    Votre mot de passe à été changé avec succés. <br />
                    <button
                        className="font-bold underline"
                        onClick={() => history.push(URL_LOGIN)}
                    >
                        Se connecter
                    </button>
                </MessageBox>
            ) : (
                <>
                    <p className="text-white mb-5">
                        Merci de renseigner le nouveau mot de passe pour le compte relié à
                        l'adresse <span className="font-bold">{user.email}</span>.
                    </p>
                    <ForgotPasswordStepTwoForm onSubmit={handleSubmit} />
                </>
            )}
        </div>
    );
};
