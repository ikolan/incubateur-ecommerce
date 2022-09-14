import 'react-toastify/dist/ReactToastify.css';
import './assets/styles/index.css';

import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';

import { store } from './redux/store';
import RoutesWithNavigation from './routes/RoutesWithNavigation';

ReactDOM.render(
    <React.StrictMode>
        <Provider store={store}>
            <RoutesWithNavigation />
        </Provider>
    </React.StrictMode>,
    document.getElementById('root'),
);
