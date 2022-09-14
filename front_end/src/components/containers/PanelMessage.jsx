import React from 'react';

import { backendApi } from '../../api/backend/backendApi';

/**
 *
 * @param {
 *      data: {
 *          color: string,
 *          message: <string> array,
 *          link: ?{
 *              type: string,
 *              url: string,
 *              params: ?{}
 *          }
 *      }
 * } props
 * @returns div to how customized messages
 *
 * @callMethod <PanelMessage data={} />
 *
 * @author NemesisMKII
 */
const PanelMessage = (props) => {
    let classes = 'flex flex-col justify-center px-2 rounded border-4 w-full h-16';
    switch (props.data.color) {
        case 'green':
            classes += ' bg-green-200 border-green-300';
            break;
        case 'red':
            classes += ' bg-red-200 border-red-300';
            break;
        default:
            classes += ' bg-green-200 border-green-300';
    }

    const onButtonClick = () => {
        if (props.data.link.type == 'Api' && props.data.link.url.includes('activate')) {
            backendApi.user.activate(props.data.link.params.activationKey).then(() => {
                props.onSuccess();
            });
        }
    };

    return (
        <div className={classes}>
            {props.data.message.map((msg) => {
                if (msg.includes('%')) {
                    var splitMsg = msg.split('%');
                    return (
                        <p key={msg}>
                            {splitMsg[0]}
                            <button
                                className="font-medium text-blue-500"
                                onClick={() => {
                                    onButtonClick();
                                }}
                            >
                                {splitMsg[1]}
                            </button>
                        </p>
                    );
                }
                return <p key={msg}>{msg}</p>;
            })}
        </div>
    );
};

export default PanelMessage;
