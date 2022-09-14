import React from 'react';

import {
    URL_USER_ADDRESS,
    URL_USER_CHANGE_PASSWORD,
    URL_USER_HOME,
    URL_USER_ORDERS,
} from '../../../constants/urls';
import { SideMenu, SideMenuItem } from '../../utils/SideMenu';

const UserSideMenu = () => {
    return (
        <SideMenu className="md:mt-12">
            <SideMenuItem link={URL_USER_HOME}>Mon profil</SideMenuItem>
            <SideMenuItem link={URL_USER_ADDRESS}>Mes adresses</SideMenuItem>
            <SideMenuItem link={URL_USER_ORDERS}>Mes commandes</SideMenuItem>
            <SideMenuItem link={URL_USER_CHANGE_PASSWORD}>
                Changer le mot de passe
            </SideMenuItem>
        </SideMenu>
    );
};

export default UserSideMenu;
