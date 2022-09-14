import React, { useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom/cjs/react-router-dom.min';

const BUTTON_STYLE = {
    NONE: 'none',
    LINK: 'link',
    PRIMARY: 'primary',
    PRIMARY_OUTLINE: 'primary-outline',
    SECONDARY: 'secondary',
    RED: 'red',
    RED_OUTLINE: 'red-outline',
};

/**
 * Standard button.
 *
 * @author Nicolas Benoit
 */
export const Button = ({
    children,
    className = '',
    doubleCheck = '',
    doubleCheckTimeout = 5,
    style = BUTTON_STYLE.PRIMARY,
    type = 'button',
    onClick = () => {},
    link,
}) => {
    const history = useHistory();
    const [clickedOneTime, setClickedOneTime] = useState(false);
    let doubleCheckTimeoutId = null;

    const handleClick = () => {
        if (doubleCheck !== '' && !clickedOneTime) {
            setClickedOneTime(true);
            doubleCheckTimeoutId = setTimeout(() => {
                setClickedOneTime(false);
            }, doubleCheckTimeout * 1000);
        } else {
            onClick();
            if (link !== null) {
                history.push(link);
            }
            setClickedOneTime(false);
            clearTimeout(doubleCheckTimeoutId);
        }
    };

    useEffect(() => {
        return () => {
            if (doubleCheckTimeoutId !== null) {
                clearTimeout(doubleCheckTimeoutId);
            }
        };
    }, []);

    let classes = className;

    switch (style) {
        case BUTTON_STYLE.NONE:
            break;

        case BUTTON_STYLE.LINK:
            classes += ' hover:font-bold';
            break;

        case BUTTON_STYLE.PRIMARY_OUTLINE:
            classes += ' btn btn-primary-outline';
            break;

        case BUTTON_STYLE.SECONDARY:
            classes += ' btn btn-secondary';
            break;

        case BUTTON_STYLE.RED:
            classes += ' btn btn-red';
            break;

        case BUTTON_STYLE.RED_OUTLINE:
            classes += ' btn btn-red-outline';
            break;

        default:
            classes += ' btn btn-primary';
            break;
    }

    return (
        <button className={classes} type={type} onClick={handleClick}>
            {clickedOneTime ? doubleCheck : children}
        </button>
    );
};
