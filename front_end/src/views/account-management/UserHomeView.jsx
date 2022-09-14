import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import UserHome from '../../components/layouts/account-management/UserHome';
import UserSideMenu from '../../components/layouts/account-management/UserSideBar';

const UserHomeView = () => {
    return (
        <MenuBasedLayout title="Mon profil" menu={<UserSideMenu />}>
            <UserHome />
        </MenuBasedLayout>
    );
};

export default UserHomeView;
