import React from 'react';

import { ListItemsExplorer } from '../../containers/ListItemsExplorer';
import { Button } from '../../utils/Button';
import { backendApi } from './../../../api/backend/backendApi';
import { formatDateTimeString } from './../../../services/dateServices';

/**
 *  Component for listing contact messages.
 */
export const AdminContacts = () => {
    const contactListRender = (item) => {
        return (
            <>
                <p className="text-xl font-bold">{item.subject}</p>
                <div className="flex justify-between">
                    <p>
                        <b>De:</b>{' '}
                        {item.firstName + ' ' + item.lastName + ' <' + item.email + '>'}
                    </p>
                    <p>{formatDateTimeString(item.sentAt)}</p>
                </div>
            </>
        );
    };

    const contactFullRender = (item, actions) => {
        return (
            <>
                <div className="flex justify-between items-start bg-gray-100 px-3 py-1">
                    <p>
                        <b>Nom:</b> {item.lastName}
                        <br />
                        <b>Prénom:</b> {item.firstName}
                        <br />
                        <b>Email: </b> {item.email}
                        <br />
                        <b>Date:</b> {formatDateTimeString(item.sentAt)}
                        <br />
                        <b>Sujet:</b> {item.subject}
                    </p>
                    <Button
                        className="flex-shrink-0 w-[128px]"
                        style="red"
                        onClick={actions['delete']}
                        doubleCheck="Sûre ?"
                    >
                        Supprimer
                    </Button>
                </div>
                <hr />
                <div className="p-5">
                    <p>{item.message}</p>
                </div>
            </>
        );
    };

    return (
        <div className="my-5">
            <ListItemsExplorer
                onLoading={(page) => {
                    return backendApi.contact.getPage(page);
                }}
                itemListRender={contactListRender}
                itemFullRender={contactFullRender}
                itemActions={{
                    delete: (item) => {
                        backendApi.contact.delete(item.id);
                        return null;
                    },
                }}
                emptyListMessage="Aucun messages"
                emptySelectionMessage="Aucun message séléctionné"
            />
        </div>
    );
};
