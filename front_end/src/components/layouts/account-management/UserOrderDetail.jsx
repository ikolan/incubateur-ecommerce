import React from 'react';
import { useEffect } from 'react';
import { useState } from 'react';
import { useParams } from 'react-router-dom/cjs/react-router-dom.min';

import { backendApi } from '../../../api/backend/backendApi';
import { formatDateString, formatDateTimeString } from '../../../services/dateServices';
import { TitledPanel } from '../../containers/TitledPanel';
import { OrderReturnForm } from '../../forms/order/OrderReturnForm';
import { BackendImage } from '../../utils/BackendImage';
import { Button } from '../../utils/Button';
import MessageBox from '../../utils/MessageBox';
import { Price } from '../../utils/Price';
import { ProductNoPicture } from '../../utils/ProductNoPicture';
import { Spinner } from '../../utils/Spinner';

const UserOrderDetail = () => {
    const [order, setOrder] = useState([]);
    const [orderReturnSended, setOrderReturnSended] = useState(false);
    const { orderReference } = useParams();

    useEffect(() => {
        backendApi.order.get(orderReference).then((data) => {
            setOrder(data.data);
            console.log(data.data);
        });
    }, []);

    const redirecttoPayment = (orderReference) => {
        backendApi.order.getPaymentLink(orderReference).then((response) => {
            window.location.href = response.data.link;
        });
    };

    if (order && order.orderReference) {
        return (
            <TitledPanel title={'Détails de la commande n°' + order.orderReference}>
                <div className="flex space-x-9">
                    <div className="w-3/4 space-y-5">
                        <div className="bg-white shadow-cartProduct p-3">
                            <p className="text-secondary font-bold text-xl">
                                Adresse de livraison
                            </p>
                            <div>
                                <p className="text-secondary-dark h-5">
                                    {order.orderUser.firstName +
                                        ' ' +
                                        order.orderUser.lastName}
                                </p>
                                <p className="text-secondary-dark h-5">
                                    {order.address.number + ' ' + order.address.road}
                                </p>
                                <p className="text-secondary-dark h-5">
                                    {order.address.zipcode + ' ' + order.address.city}
                                </p>
                                <p className="text-secondary-dark h-5">
                                    {order.address.phone}
                                </p>
                            </div>
                        </div>
                        <div className="bg-white shadow-cartProduct p-3">
                            <p className="text-secondary font-bold text-xl">
                                Récapitulatif de vos produits
                            </p>
                            <div className="space-y-3 mt-12 flex flex-col items-center">
                                {order.orderItems.map((orderItem) => (
                                    <div
                                        key={orderItem['@id']}
                                        className="flex justify-between lg:w-full 2xl:w-3/4 lg:h-16 xl:h-24"
                                    >
                                        <div className="h-full flex items-center space-x-3">
                                            <div className="h-full lg:w-16 xl:min-w-[100px]">
                                                {orderItem.product.image === undefined ? (
                                                    <ProductNoPicture className="rounded w-full text-center" />
                                                ) : (
                                                    <div className="flex justify-center items-center w-full h-full">
                                                        <BackendImage
                                                            id={
                                                                orderItem.product.image.id
                                                            }
                                                            alt={
                                                                orderItem.product.image
                                                                    .name
                                                            }
                                                        />
                                                    </div>
                                                )}
                                            </div>
                                            <p className="lg:text-sm xl:text-xl font-medium">
                                                {orderItem.product.name}
                                            </p>
                                        </div>
                                        <div className="self-center flex space-x-14 mx-3">
                                            <Price>
                                                {orderItem.quantity *
                                                    orderItem.product.price}
                                            </Price>
                                            <p className="lg:text-xl xl:text-3xl font-medium">
                                                x{orderItem.quantity}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                        {order.status.label === 'Payé' && (
                            <div className="bg-white shadow-cartProduct p-3">
                                <p className="text-secondary font-bold text-xl">
                                    Demande de retour
                                </p>
                                {order.orderReturn === undefined ? (
                                    orderReturnSended ? (
                                        <MessageBox type="success">
                                            Votre demande de retour à été envoyé.
                                        </MessageBox>
                                    ) : (
                                        <OrderReturnForm
                                            onSubmit={(values) => {
                                                backendApi.order
                                                    .makeReturnOrder(
                                                        orderReference,
                                                        values,
                                                    )
                                                    .then(() =>
                                                        setOrderReturnSended(true),
                                                    );
                                            }}
                                        />
                                    )
                                ) : (
                                    <>
                                        <p>Une demande de retour à déja été faite.</p>
                                        <p className="mt-2">
                                            Date:{' '}
                                            {formatDateTimeString(
                                                order.orderReturn.createdAt,
                                            )}
                                            .
                                        </p>
                                        <p className="mt-2">
                                            Raison: {order.orderReturn.reason}
                                        </p>
                                        <p className="mt-2">
                                            Description:
                                            <br />
                                            {order.orderReturn.description}
                                        </p>
                                    </>
                                )}
                            </div>
                        )}
                    </div>
                    <div className="w-1/4 bg-white shadow-cartProduct p-3">
                        <p className="text-secondary font-bold text-xl">Récapitulatif</p>
                        <div className="space-y-5">
                            <Price className="text-center mt-8 text-primary">
                                {order.price}
                            </Price>
                            <div className="h-[2px] w-4/5 mx-auto bg-secondary"></div>
                            <p className="text-center font-medium text-secondary text-xl">
                                Commande passée le:{' '}
                            </p>
                            <p className="text-center font-medium text-secondary text-xl">
                                {formatDateString(order.createdAt)}
                            </p>
                            <p className="text-center font-medium text-secondary text-xl">
                                Status de la commande
                            </p>
                            <p className="text-center font-medium text-secondary text-2xl">
                                {order.status.label}
                            </p>
                            {order.status.label == 'Non payé' ? (
                                <Button
                                    className="block mx-auto w-full lg:text-xs xl:text-base"
                                    onClick={() => {
                                        redirecttoPayment(order.orderReference);
                                    }}
                                >
                                    Relancer le paiement
                                </Button>
                            ) : (
                                <></>
                            )}
                        </div>
                    </div>
                </div>
            </TitledPanel>
        );
    }

    return <Spinner legend="Obtention des infos..." />;
};

export default UserOrderDetail;
