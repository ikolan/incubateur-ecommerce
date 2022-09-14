import React, { useState } from 'react';
import { useHistory } from 'react-router-dom/cjs/react-router-dom.min';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_USER_REGISTER } from '../../../constants/urls';
import { ForgetPasswordStepOneForm } from '../../forms/user/ForgotPasswordStepOneForm';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';

const CREATION_STATUS = {
    CREATED: 0,
    NOT_FOUND: 1,
};

export const ForgetPasswordStepOne = () => {
    const history = useHistory();
    const [creationStatus, setCreationStatus] = useState(null);
    const [isLoading, setIsLoading] = useState(false);

    const handleSubmit = (values) => {
        setIsLoading(true);
        backendApi.user
            .generateResetKey(values.email)
            .then(() => {
                setIsLoading(false);
                setCreationStatus(CREATION_STATUS.CREATED);
            })
            .catch(({ response }) => {
                if (response.status === 404) {
                    setIsLoading(false);
                    setCreationStatus(CREATION_STATUS.NOT_FOUND);
                }
            });
    };

    let handleMessage = () => {
        switch (creationStatus) {
            case CREATION_STATUS.CREATED:
                return (
                    <MessageBox type="success">
                        Un email à été envoyer à cette adresse. Veuillez suivre les
                        instruction pour restaurer votre mot de passe.
                    </MessageBox>
                );

            case CREATION_STATUS.NOT_FOUND:
                return (
                    <MessageBox type="error">
                        Cette adresse email n'a pas été trouvée. <br />
                        <button
                            onClick={() => history.push(URL_USER_REGISTER)}
                            className="font-bold underline"
                        >
                            Veuillez créer un compte en cliquant ici.
                        </button>
                    </MessageBox>
                );

            default:
                return <></>;
        }
    };

    return (
        <div className="py-5">
            {isLoading ? (
                <Spinner legend="Envoie en cours..." lightMode />
            ) : (
                <>
                    {handleMessage()}
                    {creationStatus === CREATION_STATUS.CREATED ? (
                        <></>
                    ) : (
                        <>
                            <p className="text-white mb-5">
                                Afin de pouvoir récupréré votre mot de passe, merci de
                                renseignez votre adresse email.
                            </p>
                            <ForgetPasswordStepOneForm onSubmit={handleSubmit} />
                        </>
                    )}
                </>
            )}
        </div>
    );
};
