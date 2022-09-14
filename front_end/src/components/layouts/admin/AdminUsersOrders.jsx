import React from 'react';
import { useEffect } from 'react';
import { useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { initialFormValues } from '../../../constants/initialFormValues';
import { accountEmail } from '../../../services/accountServices';
import { ListItemsExplorer } from '../../containers/ListItemsExplorer';
import { AdminOrderPatchForm } from '../../forms/admin/AdminOrderPatchForm';
import { BackendImage } from '../../utils/BackendImage';
import { Button } from '../../utils/Button';
import { Price } from '../../utils/Price';
import { ProductNoPicture } from '../../utils/ProductNoPicture';

const AdminOrderListRender = ({ item }) => {
    return (
        <>
            <p className="text-l font-bold">
                Commande de {item.orderUser.firstName} {item.orderUser.lastName}
            </p>
            <p>
                <span className="test-m font-medium">
                    Nombre d'articles: {item.orderItems.length}
                </span>
            </p>
        </>
    );
};

const AdminOrderFullRender = ({ item, actions }) => {
    const [isModifying, setIsModifying] = useState(false);

    useEffect(() => {
        setIsModifying(false);
    }, [item]);

    return (
        <>
            <div className="px-3 py-1 bg-gray-100">
                <p className="text-xl font-bold">Commande n°{item.orderReference}</p>
                <p className="mt-3 font-bold">
                    Nom du client:{' '}
                    {item.orderUser.firstName + ' ' + item.orderUser.lastName}
                </p>
                <p className="font-bold">Nombre d'articles: {item.orderItems.length}</p>
                {item.orderItems.length <= 0 ? (
                    ''
                ) : (
                    <>
                        <p className="font-bold">Articles commandés: </p>
                        <ul className="flex flex-col items-center space-y-2">
                            {item.orderItems.map((orderItem) => (
                                <li
                                    key={orderItem['@id']}
                                    className="w-1/2 border-2 flex"
                                >
                                    <div className="w-1/5 h-full flex items-center justify-center rounded-l">
                                        {orderItem.product.image === undefined ? (
                                            <ProductNoPicture />
                                        ) : (
                                            <div className="h-full p-1 flex items-center">
                                                <BackendImage
                                                    className="rounded max-h-full"
                                                    id={orderItem.product.image.id}
                                                    alt={orderItem.product.image.name}
                                                />
                                            </div>
                                        )}
                                    </div>
                                    <div className="flex flex-col justify-between">
                                        <p>{orderItem.product.name}</p>
                                        <p>Quantité: {orderItem.quantity}</p>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    </>
                )}
                <div className="flex flex-col items-center mt-5">
                    <p>Prix total de la commande</p>
                    <Price>{item.price}</Price>
                </div>
                {isModifying ? (
                    <AdminOrderPatchForm
                        onSubmit={(values) => {
                            backendApi.order
                                .patch(item.orderReference, {
                                    status: '/api/statuses/' + values.status,
                                })
                                .then((data) => {
                                    actions.edit(data.data.status);
                                });
                        }}
                        submitButtonChildren={'Valider'}
                        initialValues={
                            item === null
                                ? initialFormValues.adminOrder
                                : { status: item.status.id }
                        }
                    />
                ) : (
                    <>
                        <p className="mt-5 text-xl font-medium">
                            Statut de la commande: {item.status.label}
                        </p>
                        <Button
                            className="mt-3"
                            onClick={() => {
                                setIsModifying(true);
                            }}
                        >
                            Modifier le statut de la commande
                        </Button>
                    </>
                )}
            </div>
        </>
    );
};

export const AdminUsersOrders = () => {
    return (
        <div className="my-5">
            <ListItemsExplorer
                onLoading={() => {
                    return backendApi.order.getAll(accountEmail(), false);
                }}
                itemListRender={(item) => <AdminOrderListRender item={item} />}
                itemFullRender={(item, actions) => (
                    <AdminOrderFullRender item={item} actions={actions} />
                )}
                itemActions={{
                    edit: (item, payload) => {
                        return {
                            ...item,
                            status: payload,
                        };
                    },
                }}
            />
        </div>
    );
};
