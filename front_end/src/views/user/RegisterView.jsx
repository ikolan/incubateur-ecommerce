import React from 'react';
import { useHistory } from 'react-router';

import Register from '../../components/layouts/user/Register';
import { URL_HOME } from '../../constants/urls';
import { isAuthenticated } from '../../services/accountServices';

/**
 * Register view.
 *
 * @author Nicolas Benoit
 */
const RegisterView = () => {
    const history = useHistory();

    if (isAuthenticated()) {
        history.push(URL_HOME);
    }

    return <Register />;
};

export default RegisterView;
