import React from 'react';

/**
 * Simple component for making a basic panel
 *
 * @author Nicolas Benoit
 */
export const CenteredDialogPanel = ({ children, className = '' }) => {
    return (
        <div className="flex justify-center items-center min-h-[calc(100vh-100px)]">
            <div className={'bg-secondary p-6 shadow rounded-md space-y-8 ' + className}>
                {children}
            </div>
        </div>
    );
};
