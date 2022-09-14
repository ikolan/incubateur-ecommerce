import React from 'react';
import { useLocation } from 'react-router-dom';

import { CenteredDialogPanel } from '../../components/containers/CenteredDialogPanel';
import { ForgetPasswordStepOne } from '../../components/layouts/user/ForgotPasswordStepOne';
import { ForgotPasswordStepTwo } from '../../components/layouts/user/ForgotPasswordStepTwo';

export const ForgotPasswordView = () => {
    const resetKey = new URLSearchParams(useLocation().search).get('key');

    return (
        <CenteredDialogPanel className="flex justify-center items-center p-10 max-w-[800px] w-full md:w-[80vw] min-h-[100vh] md:min-h-[60vh] rounded-none md:rounded">
            <div>
                <h2 className="mt-3 text-center text-3xl font-extrabold text-white">
                    Mot de passe oublié ?
                </h2>
                <hr className="border-primary" />
                {resetKey === null ? (
                    <ForgetPasswordStepOne />
                ) : (
                    <ForgotPasswordStepTwo resetKey={resetKey} />
                )}
            </div>
        </CenteredDialogPanel>
    );
};
