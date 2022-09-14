import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminOrderReturn } from '../../components/layouts/admin/AdminOrderReturn';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';

export const AdminOrderReturnView = () => {
    return (
        <MenuBasedLayout menu={<AdminSideMenu />} title="Demandes de retour">
            <AdminOrderReturn />
        </MenuBasedLayout>
    );
};
