import React from 'react';

import { Button } from '../utils/Button';

/**
 *
 * @param {
 *      data: {
 *          title: string,
 *          content: string,
 *          button: ?string,
 *          content: ?string,
 *      }
 * } props
 * @returns div for user panel: show user informations + possible button to change that information.
 */
const PanelDiv = (props) => {
    return (
        <div className="w-7/8 min-h-100px bg-gray-200">
            <h5>{props.data.title}</h5>
            <div className="flex justify-evenly mt-4 px-5">
                <div>
                    <p className="text-xl font-medium">{props.data.content}</p>
                </div>
                {!props.data.button ? (
                    ''
                ) : (
                    <Button style="primary" link={props.data.url}>
                        Changer l'adresse mail
                    </Button>
                )}
            </div>
        </div>
    );
};

export default PanelDiv;
