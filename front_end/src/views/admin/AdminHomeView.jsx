import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';

const AdminHomeView = () => {
    return (
        <MenuBasedLayout
            title="Administration"
            menu={<AdminSideMenu />}
        ></MenuBasedLayout>
    );
};

export default AdminHomeView;
