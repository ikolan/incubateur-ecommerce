import React from 'react';
import { useHistory } from 'react-router';

import Login from '../../components/layouts/user/Login';
import { URL_HOME } from '../../constants/urls';
import { isAuthenticated } from '../../services/accountServices';

/**
 * View/Page Login
 *
 * @param {object} history
 * @author Peter Mollet
 */
const LoginView = () => {
    const history = useHistory();

    if (isAuthenticated()) {
        history.push(URL_HOME);
    }

    return <Login className="" />;
};

export default LoginView;
