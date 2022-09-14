import { concat } from 'lodash';
import React, { useEffect, useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { AddressForm } from '../../forms/account-management/AddressForm';
import { Button } from '../../utils/Button';
import { Spinner } from '../../utils/Spinner';
import { initialFormValues } from './../../../constants/initialFormValues';

const Address = ({ address, addressIndex, onEditingSucess, onDelete, onMakingMain }) => {
    const [isEditing, setIsEditing] = useState(false);
    const [editingInProgress, setEditingInProgress] = useState(false);

    const handleEditingSubmit = (values) => {
        setIsEditing(false);
        setEditingInProgress(true);
        backendApi.address
            .patch(address.id, {
                title: values.title,
                number: values.number,
                road: values.road,
                zipcode: values.zipcode,
                city: values.city,
                phone: values.phone,
            })
            .then((response) => {
                let data = response.data;
                data.isMainAddress = address.isMainAddress;
                onEditingSucess(data, addressIndex);
                setEditingInProgress(false);
            });
    };

    const handleMakingMain = () => {
        backendApi.address.patchMain(address.id);
        onMakingMain(addressIndex);
    };

    let classes = isEditing
        ? 'border rounded shadow shadow-primary m-2'
        : 'border rounded shadow m-2';

    return (
        <div className={classes}>
            {isEditing ? (
                <AddressForm
                    initialValues={address}
                    onSubmit={handleEditingSubmit}
                    onClose={() => setIsEditing(false)}
                    submitButtonValue="Editer"
                />
            ) : (
                <>
                    <div className="md:flex items-center justify-between rounded-t p-2 bg-secondary">
                        <h4 className="text-white mr-3">{address.title}</h4>
                        {address.isMainAddress ? (
                            <p className="text-primary text-lg md:text-xl font-bold italic">
                                Adresse principale
                            </p>
                        ) : (
                            <></>
                        )}
                    </div>
                    <p className="p-2">
                        {address.number} {address.road} <br />
                        {address.zipcode} {address.city} <br />
                        En cas d'absence, contacter le {address.phone}
                    </p>
                    <hr />
                    {editingInProgress ? (
                        <Spinner mode="line" legend="Edition en cours..." />
                    ) : (
                        <div className="flex items-center flex-wrap p-2">
                            <Button
                                style="primary"
                                className="w-full md:w-auto mr-2 mb-2 md:mb-1"
                                onClick={() => setIsEditing(true)}
                            >
                                Editer
                            </Button>
                            <Button
                                style="red"
                                className="w-full md:w-auto mr-2 mb-2 md:mb-1"
                                onClick={() => onDelete(addressIndex)}
                            >
                                Supprimer
                            </Button>
                            {address.isMainAddress ? (
                                <></>
                            ) : (
                                <Button
                                    style="secondary"
                                    className="w-full md:w-auto mr-2 mb-2 md:mb-1"
                                    onClick={() => handleMakingMain()}
                                >
                                    Marquer comme adresse principale
                                </Button>
                            )}
                        </div>
                    )}
                </>
            )}
        </div>
    );
};

/**
 * List of addresses.
 *
 * @author Nicolas Benoit
 */
const AddressList = () => {
    const [isLoading, setIsLoading] = useState(true);
    const [isAddingNew, setIsAddingNew] = useState(false);
    const [addresses, setAddresses] = useState([]);

    const deleteAddress = (index) => {
        backendApi.address.delete(addresses[index].id);
        setAddresses(
            addresses.filter((address, i) => {
                return index !== i;
            }),
        );
    };

    const handleNewAddress = (values) => {
        setIsAddingNew(true);
        backendApi.address.post(values).then((response) => {
            setAddresses(concat(addresses, [response.data]));
            setIsAddingNew(false);
        });
    };

    const handleEditingAddressSuccess = (data, index) => {
        setAddresses(
            addresses.map((address, i) => {
                return i !== index ? address : data;
            }),
        );
    };

    const handleMakingMainAddress = (index) => {
        setAddresses(
            addresses.map((address, i) => {
                address.isMainAddress = index === i;
                return address;
            }),
        );
    };

    useEffect(() => {
        let promise = Promise.all([
            backendApi.address.getAll(),
            backendApi.address.getMain(),
        ]);

        promise.then((responses) => {
            setAddresses(
                responses[0].data['hydra:member'].map((address) => {
                    address.isMainAddress =
                        responses[1].data !== null
                            ? address.id === responses[1].data.id
                            : false;
                    return address;
                }),
            );
            setIsLoading(false);
        });
    }, []);

    const addressesElements = addresses.map((address, i) => (
        <Address
            address={address}
            addressIndex={i}
            key={i}
            onEditingSucess={handleEditingAddressSuccess}
            onDelete={deleteAddress}
            onMakingMain={handleMakingMainAddress}
        />
    ));

    return (
        <div className="w-full p-2 mb-6">
            {!isLoading ? (
                <>
                    {addressesElements.length > 0 ? (
                        [addressesElements]
                    ) : (
                        <div className="flex justify-center m-10">
                            <h4 className="text-gray-300 font-bold">
                                Aucunes adresses trouvée.
                            </h4>
                        </div>
                    )}
                    <hr className="mt-4" />
                    <h5 className="m-2">Ajouter une nouvelle adresse</h5>
                    {isAddingNew ? (
                        <Spinner className="my-5" legend="Ajout en cours..." />
                    ) : (
                        <AddressForm
                            initialValues={initialFormValues.address}
                            onSubmit={handleNewAddress}
                            submitButtonValue="Ajouter"
                        />
                    )}
                </>
            ) : (
                <Spinner className="mt-6" legend="Chargement de vos adresses..." />
            )}
        </div>
    );
};

/**
 * Component for listing / modify user addresses.
 *
 * @author Nicolas Benoit
 */
export const UserAddress = () => {
    return <AddressList />;
};
