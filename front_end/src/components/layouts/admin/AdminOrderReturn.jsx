import React from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { formatDateTimeString } from '../../../services/dateServices';
import { ListItemsExplorer } from '../../containers/ListItemsExplorer';
import { Button } from '../../utils/Button';

export const AdminOrderReturnFullRender = (item, actions) => {
    return (
        <div className="m-2">
            <p>
                <span className="font-bold">Référence de la commande :</span>{' '}
                {item.order.orderReference}
            </p>
            <p>
                <span className="font-bold">Raison de la demande:</span> {item.reason}
            </p>
            <p>
                <span className="font-bold">Date:</span>{' '}
                {formatDateTimeString(item.createdAt)}
            </p>
            <p className="mt-2">
                <span className="font-bold">Description de la demande:</span>
                <br />
                {item.description}
            </p>
            <Button
                className="mt-2"
                style="red"
                doubleCheck="Sûre ?"
                onClick={() => actions.delete()}
            >
                Supprimer
            </Button>
        </div>
    );
};

export const AdminOrderReturn = () => {
    return (
        <ListItemsExplorer
            onLoading={() => {
                return backendApi.orderReturn.getAll();
            }}
            itemListRender={(item) => {
                return <p>Commande {item.order.orderReference}</p>;
            }}
            itemFullRender={AdminOrderReturnFullRender}
            itemActions={{
                delete: (item) => {
                    backendApi.orderReturn.delete(item.id);
                    return null;
                },
            }}
        />
    );
};
