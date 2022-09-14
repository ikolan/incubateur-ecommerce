import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminCategories } from '../../components/layouts/admin/AdminCategories';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';

export const AdminCategoriesView = () => {
    return (
        <MenuBasedLayout title="Catégories" menu={<AdminSideMenu />}>
            <AdminCategories />
        </MenuBasedLayout>
    );
};
