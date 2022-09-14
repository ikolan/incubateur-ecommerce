import React from 'react';

export const NavButton = (props) => {
    switch (props.page.type) {
        case 'previous':
            var page = '<';
            break;
        case 'next':
            var page = '>';
            break;
        case 'first':
            var page = '<<';
            break;
        case 'last':
            var page = '>>';
            break;
        default:
            var page = props.page.url;
            break;
    }
    if (props.page && props.page.url) {
        return (
            <button
                className="min-w-navBtn h-12 border-2 border-primary rounded bg-primary text-white font-bold flex items-center justify-center text-xl p-2"
                onClick={() => {
                    props.onPageChange(props.page);
                }}
            >
                {page}
            </button>
        );
    }

    return '';
};
