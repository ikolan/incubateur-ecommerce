import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import ChangePassword from '../../components/layouts/account-management/ChangePassword';
import UserSideMenu from '../../components/layouts/account-management/UserSideBar';

const ChangePasswordView = () => {
    return (
        <MenuBasedLayout title="Changer le mot de passe" menu={<UserSideMenu />}>
            <ChangePassword />
        </MenuBasedLayout>
    );
};

export default ChangePasswordView;
