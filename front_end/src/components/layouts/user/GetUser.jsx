import { useEffect, useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { isAuthenticated } from '../../../services/accountServices';
import { getPayloadToken } from '../../../services/tokenServices';

const GetUser = () => {
    const [user, setUser] = useState();

    useEffect(() => {
        if (isAuthenticated()) {
            backendApi.user.getConnected(getPayloadToken().username).then((data) => {
                setUser(data);
            });
        }
    }, []);

    return user;
};

export default GetUser;
