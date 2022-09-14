import React, { useState } from 'react';
import { useDispatch } from 'react-redux';
import { useHistory } from 'react-router';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_HOME } from '../../../constants/urls';
import { signIn } from '../../../redux/authenticationSlice';
import { CenteredDialogPanel } from '../../containers/CenteredDialogPanel';
import { LoginForm } from '../../forms/user/LoginForm';

/**
 * Component Login
 *
 * will need in props:
 *  - Submit Function
 *  - errorLog boolean
 *  - validationSchema
 *
 * See above for information
 *
 * @author Peter Mollet
 */
const Login = () => {
    const [errorLog, setErrorLog] = useState(false);
    const [loginInProgress, setLoginInProgress] = useState(false);
    const history = useHistory();
    const dispatch = useDispatch();

    const onLoginSuccess = (response) => {
        dispatch(signIn(response.data.token));
        history.push(URL_HOME);
    };

    const onLoginFailed = () => {
        setLoginInProgress(false);
        setErrorLog(true);
    };

    const handleLogin = (values) => {
        setLoginInProgress(true);
        setErrorLog(false);
        backendApi.user
            .login({
                email: values.email,
                password: values.password,
            })
            .then(onLoginSuccess)
            .catch(onLoginFailed);
    };

    return (
        <CenteredDialogPanel className="flex justify-center items-center p-10 max-w-[800px] w-full md:w-[80vw] min-h-[100vh] md:min-h-[60vh] rounded-none md:rounded">
            <div className="w-full max-w-[600px]">
                <div>
                    <h2 className="m-3 text-center text-3xl font-extrabold text-white">
                        CONNEXION
                    </h2>
                </div>

                <hr className="border-primary" />
                <LoginForm
                    errorLog={errorLog}
                    onSubmit={handleLogin}
                    loginInProgress={loginInProgress}
                />
            </div>
        </CenteredDialogPanel>
    );
};

export default Login;
