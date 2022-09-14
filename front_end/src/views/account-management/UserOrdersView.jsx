import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import UserHome from '../../components/layouts/account-management/UserHome';
import UserOrders from '../../components/layouts/account-management/UserOrders';
import UserSideMenu from '../../components/layouts/account-management/UserSideBar';

const UserOrdersView = () => {
    return (
        <MenuBasedLayout menu={<UserSideMenu />}>
            <UserOrders />
        </MenuBasedLayout>
    );
};

export default UserOrdersView;
