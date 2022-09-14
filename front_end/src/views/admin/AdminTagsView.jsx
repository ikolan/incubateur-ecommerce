import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';
import { AdminTags } from '../../components/layouts/admin/AdminTags';

export const AdminTagsView = () => {
    return (
        <MenuBasedLayout title="Tags" menu={<AdminSideMenu />}>
            <AdminTags />
        </MenuBasedLayout>
    );
};
