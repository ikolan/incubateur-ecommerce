import React from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { accountEmail } from '../../../services/accountServices';
import { formatDateString } from '../../../services/dateServices';
import { ListItemsExplorer } from '../../containers/ListItemsExplorer';
import { Button } from '../../utils/Button';

export const AdminUsers = () => {
    const userListRender = (item) => {
        return (
            <div>
                <p className="text-xl font-bold">
                    {item.firstName} {item.lastName}
                </p>
                <p>{item.email}</p>
            </div>
        );
    };

    const userFullRender = (item, actions) => {
        let buttons = [];

        if (item.isActivated) {
            buttons.push(
                <Button
                    key="deactivate"
                    style="red"
                    className="m-2 flex-shrink-0 w-[256px]"
                    doubleCheck="Sûre ?"
                    onClick={actions.deactivate}
                >
                    Désactivé cette utilisateur
                </Button>,
            );
        } else {
            buttons.push(
                <Button
                    key="reactivate"
                    style="red-outline"
                    className="m-2 flex-shrink-0 w-[256px]"
                    doubleCheck="Sûre ?"
                    onClick={actions.reactivate}
                >
                    Envoyer un mail de réactivation
                </Button>,
            );
        }

        buttons.push(
            <Button
                key="forgot-password"
                className="m-2 flex-shrink-0 w-[400px]"
                onClick={actions.forgotPassword}
                doubleCheck="Sûre ?"
            >
                Envoyer un mail de restauration de mot de passe
            </Button>,
        );

        return (
            <>
                <div className="px-3 py-1 bg-gray-100">
                    <p>
                        <span className="font-bold">Nom :</span> {item.lastName}
                    </p>
                    <p>
                        <span className="font-bold">Prénom :</span> {item.firstName}
                    </p>
                    <p>
                        <span className="font-bold">Adresse email :</span> {item.email}
                    </p>
                    <p>
                        <span className="font-bold">Date de naisssance :</span>{' '}
                        {formatDateString(item.birthDate)}
                    </p>
                    <p>
                        <span className="font-bold">Compte Activé :</span>{' '}
                        {item.isActivated ? 'Oui' : 'Non'}
                    </p>
                </div>
                <hr />
                <div className="flex">
                    {item.email !== accountEmail() ? (
                        buttons
                    ) : (
                        <div>
                            <p className="px-2 py-1 text-gray-400 font-bold italic">
                                Vous ne pouvez agir sur votre compte
                            </p>
                        </div>
                    )}
                </div>
            </>
        );
    };

    return (
        <div className="my-5">
            <ListItemsExplorer
                onLoading={(page, search) => {
                    return search === ''
                        ? backendApi.user.getPage(page)
                        : backendApi.user.getSearch(search, page);
                }}
                itemListRender={userListRender}
                itemFullRender={userFullRender}
                itemActions={{
                    deactivate: (item) => {
                        backendApi.user.deactivate(item.email);
                        item.isActivated = false;
                        return item;
                    },
                    reactivate: (item) => {
                        backendApi.user.reactivationRequest(item.email);
                        return item;
                    },
                    forgotPassword: (item) => {
                        backendApi.user.generateResetKey(item.email);
                        return item;
                    },
                }}
                emptySelectionMessage="Aucun utilisateur séléctionné"
                search
            />
        </div>
    );
};
