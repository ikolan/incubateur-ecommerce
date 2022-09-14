import React, { useEffect, useState } from 'react';
import ReactSlider from 'react-slider';

export const PriceSlider = ({ onAfterChange, value, defaultValue }) => {
    const [displayValue, setDisplayValue] = useState([]);

    const renderThumb = (props, state) => {
        return <div {...props}></div>;
    };

    useEffect(() => {
        displayValue[0] = value[0];
        displayValue[1] = value[1];
    }, value);

    return (
        <>
            <div className="w-full h-[30px]">
                <ReactSlider
                    className="slider"
                    min={defaultValue[0]}
                    max={defaultValue[1]}
                    step={10}
                    defaultValue={defaultValue}
                    value={value}
                    thumbClassName="slider-thumb"
                    renderThumb={renderThumb}
                    onChange={(values) => {
                        setDisplayValue(values);
                    }}
                    onAfterChange={onAfterChange}
                    trackClassName="slider-track"
                    pearling
                    minDistance={100}
                />
            </div>

            {value.length !== 0 && (
                <p className="w-full text-center">
                    Entre{' '}
                    <span className="text-xl font-bold text-primary">
                        {displayValue[0] === null || displayValue[0] === undefined
                            ? null
                            : displayValue[0].toString().replace('.', ',')}{' '}
                        €
                    </span>{' '}
                    et{' '}
                    <span className="text-xl font-bold text-primary">
                        {displayValue[1] === null || displayValue[1] === undefined
                            ? null
                            : displayValue[1].toString().replace('.', ',')}{' '}
                        €
                    </span>
                </p>
            )}
        </>
    );
};
