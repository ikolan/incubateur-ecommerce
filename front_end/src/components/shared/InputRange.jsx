import React from 'react';

export const InputRange = () => {
    var inputMin = document.getElementById('min');
    var inputMax = document.getElementById('max');
    console.log(inputMin);

    const onClick = (e) => {
        e.target.style.left = 0;
    };

    return (
        <div className="w-3/4 h-3 rounded flex items-center border-2">
            <div
                id="min"
                className="h-4 w-4 rounded-full bg-blue-500 absolute"
                onClick={onClick}
            ></div>
            <div
                id="max"
                className="h-4 w-4 rounded-full bg-blue-500 absolute"
                onClick={onClick}
            ></div>
        </div>
    );
};
