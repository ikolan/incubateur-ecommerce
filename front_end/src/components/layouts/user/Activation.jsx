import React, { useEffect, useState } from 'react';
import { useDispatch } from 'react-redux';
import { useHistory } from 'react-router';
import { useLocation } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_NOT_FOUND } from '../../../constants/urls';
import { signOut } from '../../../redux/authenticationSlice';
import { CenteredDialogPanel } from '../../containers/CenteredDialogPanel';
import { Spinner } from '../../utils/Spinner';

/**
 * Activation of a user account.
 *
 * @author Nicolas Benoit
 */
const Activation = () => {
    const history = useHistory();
    const [isActivated, setIsActivated] = useState(false);
    const search = useLocation().search;
    const activationKey = new URLSearchParams(search).get('key');
    const dispatch = useDispatch();

    if (activationKey == null) {
        return <>{history.push(URL_NOT_FOUND)}</>;
    }

    useEffect(() => {
        backendApi.user
            .activate(activationKey)
            .then(() => {
                dispatch(signOut());
                setIsActivated(true);
            })
            .catch(() => {
                return <>{history.push(URL_NOT_FOUND)}</>;
            });
    }, []);

    return (
        <CenteredDialogPanel>
            {!isActivated ? (
                <>
                    <Spinner legend="Activation en cours..." lightMode />
                </>
            ) : (
                <>
                    <p className="text-center text-white">Votre compte a été activé.</p>
                </>
            )}
        </CenteredDialogPanel>
    );
};

export default Activation;
