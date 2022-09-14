import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';
import { AdminUsersOrders } from '../../components/layouts/admin/AdminUsersOrders';

export const AdminUsersOrdersView = () => {
    return (
        <MenuBasedLayout title="Commandes utilisateur" menu={<AdminSideMenu />}>
            <AdminUsersOrders />
        </MenuBasedLayout>
    );
};
