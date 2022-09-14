import { Disclosure, Transition } from '@headlessui/react';
import { MenuIcon, XIcon } from '@heroicons/react/solid';
import { Field, Form, Formik } from 'formik';
import React, { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { Link } from 'react-router-dom';
import { useHistory } from 'react-router-dom/cjs/react-router-dom.min';

import { backendApi } from '../../api/backend/backendApi';
import adminIcon from '../../assets/icons/admin.svg';
import cartIcon from '../../assets/icons/cart.svg';
import logoutIcon from '../../assets/icons/logout.svg';
import userIcon from '../../assets/icons/user.svg';
import { Button } from '../utils/Button';
import Input from '../utils/Input';
import { NavbarIconButton } from '../utils/NavbarIconButton';
import logo from './../../assets/images/logo.png';
import {
    URL_ADMIN_HOME,
    URL_CART,
    URL_HOME,
    URL_LOGIN,
    URL_PRODUCTS_HOME,
    URL_USER_HOME,
} from './../../constants/urls';
import { selectIsLogged, signOut } from './../../redux/authenticationSlice';
import { accountRoles } from './../../services/accountServices';

const Navbar = () => {
    const [categories, setCategories] = useState([]);

    useEffect(() => {
        backendApi.category.getAll().then((response) => {
            setCategories(
                response.data['hydra:member'].map((category) => category.label),
            );
        });
    }, []);

    const menu = (
        <NavbarMenu>
            {categories.map((category) => {
                let url = new URLSearchParams();
                url.set('category', category);
                return (
                    <NavbarMenuItem
                        key={category}
                        link={URL_PRODUCTS_HOME + '?' + url.toString()}
                    >
                        {category}
                    </NavbarMenuItem>
                );
            })}
            <NavbarMenuItem link={URL_PRODUCTS_HOME}>Tous nos produits</NavbarMenuItem>
        </NavbarMenu>
    );

    return (
        <Disclosure
            as="nav"
            className="flex flex-col justify-center top-0 fixed z-50 w-full bg-[#0d195a] min-h-[100px]"
        >
            {({ open }) => (
                <>
                    <div className="px-8">
                        <div className="flex justify-between items-center pt-2">
                            <div className="flex-shrink-0">
                                <Link to={URL_HOME}>
                                    <img
                                        className="h-[64px] w-auto cursor-pointer"
                                        src={logo}
                                        alt=""
                                        height={64}
                                    />
                                </Link>
                            </div>

                            <div className="hidden md:flex w-full max-w-[600px] ml-[25px]">
                                <SearchBar />
                            </div>

                            <div className="hidden md:flex items-center justify-end">
                                <ConnectionBtn />
                            </div>

                            <div className="-mr-2 flex md:hidden">
                                {/* Mobile menu button */}
                                <Disclosure.Button
                                    className="inline-flex items-center justify-center p-2 rounded-md hover:text-white hover:bg-primary 
                                    focus:outline-none transform active:scale-95 active:ring-2 active:ring-offset-2 active:ring-primary "
                                >
                                    {open ? (
                                        <XIcon
                                            className="block h-10 w-10 text-white"
                                            aria-hidden="true"
                                        />
                                    ) : (
                                        <MenuIcon
                                            className="block h-10 w-10 text-white"
                                            aria-hidden="true"
                                        />
                                    )}
                                </Disclosure.Button>
                            </div>
                        </div>
                    </div>

                    <Transition
                        enter="transition"
                        enterFrom="transform opacity-0 scale-95"
                        enterTo="transform opacity-100 scale-100"
                        leave="transition"
                        leaveFrom="transform opacity-100 scale-100"
                        leaveTo="transform opacity-0 scale-95"
                    >
                        <Disclosure.Panel className="p-4 md:hidden ">
                            <div className="my-2">
                                <SearchBar />
                            </div>
                            <hr />
                            <div className="p-4">
                                <ConnectionBtn />
                                {menu}
                            </div>
                        </Disclosure.Panel>
                    </Transition>

                    <div className="hidden md:block">{menu}</div>
                </>
            )}
        </Disclosure>
    );
};

export default Navbar;

const ConnectionBtn = () => {
    const history = useHistory();
    const isLogged = useSelector(selectIsLogged);
    const dispatch = useDispatch();

    if (isLogged)
        return (
            <div className="flex justify-center items-center">
                {accountRoles().includes('ROLE_ADMIN') ||
                accountRoles().includes('ROLE_SELLER') ? (
                    <NavbarIconButton
                        icon={adminIcon}
                        label="Admin"
                        onClick={() => history.push(URL_ADMIN_HOME)}
                    />
                ) : (
                    <></>
                )}
                <NavbarIconButton
                    icon={cartIcon}
                    label="Panier"
                    onClick={() => history.push(URL_CART)}
                />
                <NavbarIconButton
                    icon={userIcon}
                    label="Compte"
                    onClick={() => history.push(URL_USER_HOME)}
                />
                <NavbarIconButton
                    icon={logoutIcon}
                    label="Se déconnecter"
                    onClick={() => {
                        dispatch(signOut());
                        history.push(URL_LOGIN);
                    }}
                />
            </div>
        );
    else
        return (
            <div className="flex justify-center items-center">
                <NavbarIconButton
                    icon={cartIcon}
                    label="Panier"
                    onClick={() => history.push(URL_CART)}
                />
                <NavbarIconButton
                    icon={userIcon}
                    label="Se connecter"
                    onClick={() => history.push(URL_LOGIN)}
                />
            </div>
        );
};

const SearchBar = () => {
    const history = useHistory();

    return (
        <Formik
            initialValues={{ search: '' }}
            onSubmit={(values) => {
                history.push(URL_PRODUCTS_HOME + '?search=' + values.search);
            }}
        >
            <Form className="w-full flex">
                <div className="w-full border-0">
                    <Field
                        className="rounded-none rounded-l-lg h-[45px]"
                        name="search"
                        component={Input}
                        placeholder="Recherche"
                    />
                </div>
                <Button style="primary" type="submit" className="rounded-l-none">
                    Recherche
                </Button>
            </Form>
        </Formik>
    );
};

const NavbarMenu = ({ children }) => {
    return (
        <div className="flex justify-center items-start md:max-h-[30px] mt-2 ">
            <div className="flex flex-col md:flex-row bg-primary rounded p-2 w-[800px] md:h-[60px]">
                {children}
            </div>
        </div>
    );
};

const NavbarMenuItem = ({ children, link }) => {
    const history = useHistory();

    const handleClick = () => {
        history.push(link);
    };

    return (
        <button
            onClick={handleClick}
            className="flex-1 p-4 md:p-0 text-white font-bold md:border-r-2 md:last:border-0 rounded md:rounded-none md:first:rounded-l md:last:rounded-r hover:bg-[#FFFFFF20] active:bg-[#FFFFFF30]"
        >
            {children.toUpperCase()}
        </button>
    );
};
