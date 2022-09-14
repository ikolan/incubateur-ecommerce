import React, { useEffect, useState } from 'react';
import { useHistory, useParams } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_NOT_FOUND } from '../../../constants/urls';
import { accountEmail } from '../../../services/accountServices';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';

export const ValidateOrder = () => {
    const history = useHistory();
    const { orderReference } = useParams();
    const [isUpdatingOrder, setIsUpdatingOrder] = useState(true);

    useEffect(() => {
        backendApi.order.updateState(orderReference).then((response) => {
            setIsUpdatingOrder(false);
            if (response.data.status.label !== 'Payé') {
                history.push(URL_NOT_FOUND);
            } else {
                backendApi.lineCarts.deleteAll(accountEmail());
            }
        });
    }, []);

    return (
        <div className="mt-5 mx-10">
            {isUpdatingOrder ? (
                <Spinner />
            ) : (
                <MessageBox className="text-xl text-center font-bold" type="success">
                    Votre commande à été validé. Merci de votre confiance.
                </MessageBox>
            )}
        </div>
    );
};
