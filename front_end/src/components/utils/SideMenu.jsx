import React from 'react';
import { Link } from 'react-router-dom';

export const SideMenuItem = ({ children, link }) => {
    return (
        <Link to={link}>
            <li className="p-1 rounded hover:bg-primary hover:bg-opacity-20 hover:font-bold hover:text-l active:bg-opacity-30">
                {children}
            </li>
        </Link>
    );
};

export const SideMenu = ({ children, className = '', title = 'Menu' }) => {
    return (
        <div
            className={
                'w-full md:w-[270px] border-2 shadow-lg rounded-lg flex-shrink-0 ' +
                className
            }
        >
            <div className="bg-secondary p-2 rounded-t-lg">
                <h6 className="text-white">{title.toUpperCase()}</h6>
            </div>
            <ul className="m-1 text-xl">{children}</ul>
        </div>
    );
};
