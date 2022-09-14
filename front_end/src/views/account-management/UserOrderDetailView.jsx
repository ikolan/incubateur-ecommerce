import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import UserOrderDetail from '../../components/layouts/account-management/UserOrderDetail';
import UserSideMenu from '../../components/layouts/account-management/UserSideBar';

const UserOrderDetailView = () => {
    return (
        <MenuBasedLayout menu={<UserSideMenu />}>
            <UserOrderDetail />
        </MenuBasedLayout>
    );
};

export default UserOrderDetailView;
