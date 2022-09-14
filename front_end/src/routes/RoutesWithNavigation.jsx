import React from 'react';
import { BrowserRouter } from 'react-router-dom';

import { Footer } from '../components/layouts/Footer';
import Navbar from './../components/layouts/Navbar';
import Routes from './Routes';

/**
 * Component RouteWithNavigation
 * To create the structure of the application (nav bar, routes, toast, etc...)
 *
 * @author Peter Mollet
 */
const RoutesWithNavigation = () => {
    return (
        <BrowserRouter>
            <div className="min-h-full flex flex-col bg-white cursor-default">
                <Navbar />
                <main className="grow mt-[100px] md:mt-[120px] min-h-[calc(100vh-120px)]">
                    <Routes />
                </main>
                <Footer />
            </div>
        </BrowserRouter>
    );
};

export default RoutesWithNavigation;
