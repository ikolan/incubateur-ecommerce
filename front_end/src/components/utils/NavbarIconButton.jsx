import React from 'react';

/**
 * Button for the navbar.
 *
 * @author Nicolas Benoit
 */
export const NavbarIconButton = ({ icon, label, onClick = () => {} }) => {
    return (
        <div
            className="flex flex-col flex-shrink-0 min-w-[70px] items-center p-2 rounded-lg hover:bg-[#FFFFFF10] active:bg-[#FFFFFF30]"
            onClick={onClick}
            onKeyDown={() => {}}
            role="button"
            tabIndex={0}
        >
            <img src={icon} alt={label} width="26" />
            <p className="pt-3 text-sm text-white text-center">{label}</p>
        </div>
    );
};
