import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { UserAddress } from '../../components/layouts/account-management/UserAddress';
import UserSideMenu from '../../components/layouts/account-management/UserSideBar';

const UserAddressesView = () => {
    return (
        <MenuBasedLayout title="Mes adresses" menu={<UserSideMenu />}>
            <UserAddress />
        </MenuBasedLayout>
    );
};

export default UserAddressesView;
