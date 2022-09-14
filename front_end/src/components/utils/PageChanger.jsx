import React, { useEffect, useState } from 'react';

/**
 * Component for change a page.
 *
 * @param currentPage
 * @param totalPages
 * @param renderButton Function call during the render of a button.
 * @author Nicolas Benoit
 */
export const PageChanger = ({
    currentPage = 1,
    totalPages = 1,
    renderButton = (page, selected) => {},
}) => {
    const [buttons, setButtons] = useState([]);

    useEffect(() => {
        let b = [];
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                b.push(<div key={i}>{renderButton(i, true)}</div>);
            } else {
                b.push(<div key={i}>{renderButton(i, false)}</div>);
            }
        }
        setButtons(b);
    }, [currentPage, totalPages]);

    return <>{buttons}</>;
};
