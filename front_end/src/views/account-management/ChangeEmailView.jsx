import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import ChangeEmail from '../../components/layouts/account-management/ChangeEmail';
import UserSideMenu from '../../components/layouts/account-management/UserSideBar';

const ChangeEmailView = () => {
    return (
        <MenuBasedLayout title="Changer l'adresse mail" menu={<UserSideMenu />}>
            <ChangeEmail />
        </MenuBasedLayout>
    );
};

export default ChangeEmailView;
