import React from 'react';

import { Button } from '../utils/Button';

/**
 *
 * @author NemesisMKII
 * @description Generates a modal with given params in props:
 * {
 *      title: title of the modal
 *      content: content of the modal
 *      button: text on button
 * }
 * Will be modifed to ensure larger utilisations.
 */
const Modal = ({ children, content, buttons }) => {
    const buttonElements = buttons.map((elem) => {
        return (
            <Button
                className="m-2"
                style={elem.style}
                onClick={elem.onClick}
                key={elem.content}
            >
                {elem.content}
            </Button>
        );
    });

    return (
        <div
            id="modal"
            className="w-full h-full bg-gray-900 bg-opacity-80 top-0 left-0 fixed sticky-0 z-50 flex items-center justify-center"
        >
            <div className="w-full mx-5 md:max-w-[500px] md:m-0 bg-white rounded">
                <header className="flex items-center font-bold p-3 bg-secondary rounded-t">
                    <h6 className="text-white">{content.title}</h6>
                </header>
                <div className="p-3">
                    <p>{children}</p>
                </div>
                <hr />
                <footer className="flex items-center justify-center">
                    {buttonElements}
                </footer>
            </div>
        </div>
    );
};

export default Modal;
