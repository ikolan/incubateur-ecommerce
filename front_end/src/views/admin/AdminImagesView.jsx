import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminImages } from '../../components/layouts/admin/AdminImages';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';

export const AdminImagesView = () => {
    return (
        <MenuBasedLayout title="Images" menu={<AdminSideMenu />}>
            <AdminImages />
        </MenuBasedLayout>
    );
};
