import React from 'react';

import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import ChangeBirthDate from '../../components/layouts/account-management/ChangeBirthDate';
import UserSideMenu from '../../components/layouts/account-management/UserSideBar';

const ChangeBirthDateView = () => {
    return (
        <MenuBasedLayout title="Changer la date de naissance" menu={<UserSideMenu />}>
            <ChangeBirthDate />
        </MenuBasedLayout>
    );
};

export default ChangeBirthDateView;
