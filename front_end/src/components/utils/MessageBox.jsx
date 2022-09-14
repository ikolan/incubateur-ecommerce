import React from 'react';

/**
 * Component for a simple message box.
 *
 * @param {String} type Type of the message.
 *
 * @author Nicolas Benoit
 */
const MessageBox = ({ children, type, className = '' }) => {
    let classes = 'rounded p-4 border ' + className;

    switch (type) {
        case 'error':
            classes += ' bg-red-500 text-white border-red-700';
            break;
        case 'success':
            classes += ' bg-green-400 text-white border-green-200';
            break;
    }

    return <div className={classes}>{children}</div>;
};

export default MessageBox;
