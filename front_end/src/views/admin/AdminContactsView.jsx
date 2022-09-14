import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminContacts } from '../../components/layouts/admin/AdminContacts';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';

export const AdminContactsView = () => {
    return (
        <MenuBasedLayout title="Messages de contact" menu={<AdminSideMenu />}>
            <AdminContacts />
        </MenuBasedLayout>
    );
};
