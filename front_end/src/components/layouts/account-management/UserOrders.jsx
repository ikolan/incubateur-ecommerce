import React from 'react';
import { useEffect } from 'react';
import { useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_USER_ORDER_DETAIL } from '../../../constants/urls';
import { accountEmail } from '../../../services/accountServices';
import { price } from '../../../services/priceService';
import { TitledPanel } from '../../containers/TitledPanel';
import { Button } from '../../utils/Button';
import { Spinner } from '../../utils/Spinner';

const Order = ({ order }) => {
    return (
        <div className="space-y-5 border-2 p-0">
            <div className="md:flex items-center justify-between rounded-t p-3 bg-secondary h-1/4">
                <h5 className="text-white mr-3">Commande n°{order.orderReference}</h5>
                <h5 className="text-white text-3xl">{order.status.label}</h5>
            </div>
            <div className="m-0 flex justify-between items-center h-3/4">
                <div className="p-3">
                    <p className="text-xl">
                        Nombre d'articles: {order.orderItems.length}
                    </p>
                    <p className="text-xl">
                        Prix total de la commande: {price(order.price)}
                    </p>
                </div>
                <div>
                    <Button
                        link={URL_USER_ORDER_DETAIL.replace(
                            ':orderReference',
                            order.orderReference,
                        )}
                    >
                        Voir la commande
                    </Button>
                </div>
            </div>
        </div>
    );
};

const UserOrders = () => {
    const [orders, setOrders] = useState();

    useEffect(() => {
        backendApi.order.getAll(accountEmail()).then((data) => {
            setOrders(data.data['hydra:member']);
        });
    }, []);

    if (orders && orders.length > 0) {
        return (
            <TitledPanel title="Mes commandes">
                <div className="space-y-5">
                    {orders.map((order) => (
                        <Order key={order.orderReference} order={order}></Order>
                    ))}
                </div>
            </TitledPanel>
        );
    } else if (orders && orders.length <= 0) {
        return (
            <TitledPanel title="Mes commandes">
                <p>Vous n'avez pas encore passé de commandes.</p>
            </TitledPanel>
        )
    }
    return <Spinner legend="Obtention des infos..." />;
};

export default UserOrders;
