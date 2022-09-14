import React, { useEffect } from 'react';
import { Route, Switch, useLocation } from 'react-router-dom';

import { PrivateRoute } from '../components/shared/PrivateRoute';
import { ROLE_ADMIN, ROLE_SELLER, ROLE_USER } from '../constants/roles';
import * as URL from '../constants/urls';
import { customHistory } from '../services/historyServices';
import ChangeBirthDateView from '../views/account-management/ChangeBirthDateView';
import ChangeEmailView from '../views/account-management/ChangeEmailView';
import ChangeNameView from '../views/account-management/ChangeNameView';
import ChangePasswordView from '../views/account-management/ChangePasswordView';
import UserAddressesView from '../views/account-management/UserAddressesView';
import UserHomeView from '../views/account-management/UserHomeView';
import UserOrderDetailView from '../views/account-management/UserOrderDetailView';
import UserOrdersView from '../views/account-management/UserOrdersView';
import { AdminAddImageView } from '../views/admin/AdminAddImageView';
import { AdminCategoriesView } from '../views/admin/AdminCategoriesView';
import { AdminContactsView } from '../views/admin/AdminContactsView';
import { AdminEditProductView } from '../views/admin/AdminEditProductView';
import AdminHomeView from '../views/admin/AdminHomeView';
import { AdminImagesView } from '../views/admin/AdminImagesView';
import { AdminOrderReturnView } from '../views/admin/AdminOrderReturnView';
import { AdminProductsView } from '../views/admin/AdminProductsView';
import { AdminTagsView } from '../views/admin/AdminTagsView';
import { AdminUsersOrdersView } from '../views/admin/AdminUsersOrdersView';
import { AdminUsersView } from '../views/admin/AdminUsersView';
import CartView from '../views/cart/CartView';
import { ContactView } from '../views/ContactView';
import HomeView from '../views/HomeView';
import { ValidateOrderView } from '../views/order/ValidateOrderView';
import PageNotFoundView from '../views/PageNotFoundView';
import ProductDetailView from '../views/products/ProductDetailView';
import ProductListingView from '../views/products/ProductListingView';
import { ForgotPasswordView } from '../views/user/ForgotPasswordView';
import LoginView from '../views/user/LoginView';
import RegisterView from '../views/user/RegisterView';
import UserActivationView from '../views/user/UserActivationView';

/**
 * Sub routes for administration panel.
 *
 * @author Nicolas Benoit
 */
const AdminRoutes = () => {
    return (
        <Switch>
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_HOME}
                component={AdminHomeView}
            />
            <PrivateRoute
                path={URL.URL_ADMIN_USERS}
                component={AdminUsersView}
                roles={['ROLE_ADMIN']}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_CATEGORIES}
                component={AdminCategoriesView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_TAGS}
                component={AdminTagsView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_PRODUCTS}
                component={AdminProductsView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_ADD_PRODUCT}
                component={AdminEditProductView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_EDIT_PRODUCT}
                component={AdminEditProductView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_IMAGES}
                component={AdminImagesView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_ADD_IMAGE}
                component={AdminAddImageView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_CONTACT_MESSAGES}
                component={AdminContactsView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_USERS_ORDERS}
                component={AdminUsersOrdersView}
            />
            <PrivateRoute
                roles={['ROLE_SELLER']}
                path={URL.URL_ADMIN_ORDER_RETURN}
                component={AdminOrderReturnView}
            />
        </Switch>
    );
};

/**
 * Routes of the application
 * with public and private route
 *
 * @author Peter Mollet
 */
const Routes = () => {
    // Scroll to the top when route change.
    const location = useLocation();
    useEffect(() => {
        window.scrollTo(0, 0);
    }, [location.pathname]);

    return (
        <Switch history={customHistory}>
            <Route exact path={URL.URL_HOME} component={HomeView} />
            <Route path={URL.URL_LOGIN} component={LoginView} />
            <Route path={URL.URL_USER_REGISTER} component={RegisterView} />
            <Route path={URL.URL_USER_ACTIVATION} component={UserActivationView} />
            <PrivateRoute
                roles={[ROLE_USER]}
                exact
                path={URL.URL_USER_HOME}
                component={UserHomeView}
            />
            <PrivateRoute
                roles={[ROLE_USER]}
                exact
                path={URL.URL_USER_CHANGE_PASSWORD}
                component={ChangePasswordView}
            />
            <PrivateRoute
                roles={[ROLE_USER]}
                exact
                path={URL.URL_USER_CHANGE_EMAIL}
                component={ChangeEmailView}
            />
            <PrivateRoute
                roles={[ROLE_USER]}
                exact
                path={URL.URL_USER_CHANGE_NAME}
                component={ChangeNameView}
            />
            <PrivateRoute
                roles={[ROLE_USER]}
                exact
                path={URL.URL_USER_CHANGE_BIRTHDATE}
                component={ChangeBirthDateView}
            />
            <PrivateRoute
                roles={[ROLE_USER]}
                path={URL.URL_USER_ADDRESS}
                component={UserAddressesView}
            />
            <PrivateRoute
                roles={[ROLE_USER]}
                path={URL.URL_USER_ORDERS}
                component={UserOrdersView}
            />
            <PrivateRoute
                roles={[ROLE_USER]}
                path={URL.URL_USER_ORDER_DETAIL}
                component={UserOrderDetailView}
            />
            <Route path={URL.URL_FORGOT_PASSWORD} component={ForgotPasswordView} />
            <Route exact path={URL.URL_PRODUCTS_HOME} component={ProductListingView} />
            <Route exact path={URL.URL_PRODUCTS_DETAIL} component={ProductDetailView} />
            <Route exact path={URL.URL_CART} component={CartView} />
            <Route path={URL.URL_CONTACT} component={ContactView} />
            <Route path={URL.URL_ORDER_VALID} component={ValidateOrderView} />

            <PrivateRoute
                path={URL.URL_ADMIN}
                component={AdminRoutes}
                roles={[ROLE_ADMIN, ROLE_SELLER]}
            />

            <Route path="*" component={PageNotFoundView} />
        </Switch>
    );
};

export default Routes;
