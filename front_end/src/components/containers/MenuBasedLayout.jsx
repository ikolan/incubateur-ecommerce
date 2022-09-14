import React from 'react';

export const MenuBasedLayout = ({ children, menu, title }) => {
    return (
        <div className="flex justify-center">
            <div className="md:flex justify-start items-start w-full mx-4 md:mx-12">
                {menu}
                <div className="w-full mt-4 md:ml-4">
                    <h3 className="mt-6 font-bold">{title}</h3>
                    <div className="w-full h-3/4">{children}</div>
                </div>
            </div>
        </div>
    );
};
