import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminProducts } from '../../components/layouts/admin/AdminProducts';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';

export const AdminProductsView = () => {
    return (
        <MenuBasedLayout title="Produits" menu={<AdminSideMenu />}>
            <AdminProducts />
        </MenuBasedLayout>
    );
};
