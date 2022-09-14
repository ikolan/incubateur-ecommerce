import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import ChangeName from '../../components/layouts/account-management/ChangeName';
import UserSideMenu from '../../components/layouts/account-management/UserSideBar';

const ChangeNameView = () => {
    return (
        <MenuBasedLayout title="Changer le nom et prénom" menu={<UserSideMenu />}>
            <ChangeName />
        </MenuBasedLayout>
    );
};

export default ChangeNameView;
