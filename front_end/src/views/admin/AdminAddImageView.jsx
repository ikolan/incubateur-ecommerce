import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminAddImage } from '../../components/layouts/admin/AdminAddImage';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';

export const AdminAddImageView = () => {
    return (
        <MenuBasedLayout title="Ajouter une image" menu={<AdminSideMenu />}>
            <AdminAddImage />
        </MenuBasedLayout>
    );
};
