import React from 'react';

/**
 * Simple panel with a title.
 *
 * @author Nicolas Benoit
 */
export const TitledPanel = ({ children, title }) => {
    return (
        <div className="border rounded shadow">
            <div className="rounded-t bg-secondary p-3">
                <h4 className="text-white">{title}</h4>
            </div>
            <div className="p-3">{children}</div>
        </div>
    );
};
