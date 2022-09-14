import React from 'react';

const SPINNER_MODE = {
    NORMAL: 'normal',
    LINE: 'line',
};

/**
 * A spinner show when thing are in progress.
 *
 * @author Nicolas Benoit
 */
export const Spinner = ({
    className = '',
    mode = SPINNER_MODE.NORMAL,
    lightMode = false,
    legend,
}) => {
    let containerClassName = 'flex justify-center items-center content-center w-full';
    let spinnerClassName = 'border-solid border-primary animate-spin';
    let legendClassName = 'italic';

    switch (mode) {
        case SPINNER_MODE.LINE:
            containerClassName += ' flex-row h-[64px]';
            spinnerClassName += ' w-[38px] h-[38px] border-[8px] rounded-xl';
            legendClassName += ' ml-4';
            break;

        default:
            containerClassName += ' flex-col h-[128px]';
            spinnerClassName += ' w-[92px] h-[92px] border-[15px] rounded-3xl';
            legendClassName += ' mt-4';
            break;
    }

    if (lightMode) {
        legendClassName += ' text-white';
    }

    return (
        <div className={containerClassName + ' ' + className}>
            <div className={spinnerClassName}></div>
            {legend !== null ? <p className={legendClassName}>{legend}</p> : <></>}
        </div>
    );
};
