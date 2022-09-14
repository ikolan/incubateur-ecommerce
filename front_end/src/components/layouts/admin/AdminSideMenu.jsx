import React from 'react';

import {
    URL_ADMIN_CATEGORIES,
    URL_ADMIN_CONTACT_MESSAGES,
    URL_ADMIN_IMAGES, URL_ADMIN_ORDER_RETURN,
    URL_ADMIN_PRODUCTS,
    URL_ADMIN_TAGS,
    URL_ADMIN_USERS,
    URL_ADMIN_USERS_ORDERS,
} from '../../../constants/urls';
import { accountRoles } from '../../../services/accountServices';
import { SideMenu, SideMenuItem } from '../../utils/SideMenu';

export const AdminSideMenu = () => {
    return (
        <SideMenu title="Administration" className="md:mt-12">
            {accountRoles().includes('ROLE_ADMIN') ? (
                <SideMenuItem link={URL_ADMIN_USERS}>Utilisateurs</SideMenuItem>
            ) : (
                <></>
            )}
            <SideMenuItem link={URL_ADMIN_CATEGORIES}>Catégories</SideMenuItem>
            <SideMenuItem link={URL_ADMIN_TAGS}>Tags</SideMenuItem>
            <SideMenuItem link={URL_ADMIN_PRODUCTS}>Produits</SideMenuItem>
            <SideMenuItem link={URL_ADMIN_IMAGES}>Images</SideMenuItem>
            <SideMenuItem link={URL_ADMIN_USERS_ORDERS}>
                Commandes utilisateur
            </SideMenuItem>
            <SideMenuItem link={URL_ADMIN_ORDER_RETURN}>Demandes de retour</SideMenuItem>
            <SideMenuItem link={URL_ADMIN_CONTACT_MESSAGES}>
                Messages de contact
            </SideMenuItem>
        </SideMenu>
    );
};
