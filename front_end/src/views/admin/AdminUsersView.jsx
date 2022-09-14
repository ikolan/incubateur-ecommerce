import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';
import { AdminUsers } from '../../components/layouts/admin/AdminUsers';

export const AdminUsersView = () => {
    return (
        <MenuBasedLayout title="Utilisateurs" menu={<AdminSideMenu />}>
            <AdminUsers />
        </MenuBasedLayout>
    );
};
