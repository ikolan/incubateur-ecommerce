import React from 'react';

export const ProductNoPicture = ({ className = '', ...props }) => {
    return (
        <div
            className={`bg-gray-300 h-full w-full flex justify-center items-center ${className}`}
            {...props}
        >
            <p className="text-xl font-bold text-gray-500">Aucune image</p>
        </div>
    );
};
