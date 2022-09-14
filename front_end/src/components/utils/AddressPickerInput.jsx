import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

import { backendApi } from '../../api/backend/backendApi';
import { URL_USER_ADDRESS } from '../../constants/urls';
import { accountEmail } from '../../services/accountServices';
import { FormFieldErrorMessage } from './FormFieldErrorMessage';

const AddressPickerInputOptionRender = ({ address, ...rest }) => {
    return (
        <div {...rest}>
            <p className="font-bold">{address.title}</p>
            <p>
                {address.number} {address.road} <br />
                {address.zipcode} {address.city}
            </p>
        </div>
    );
};

const AddressPickerInputList = ({ addresses, selected, onItemClick }) => {
    return addresses.map((address) => {
        let classes = 'p-2 rounded-xl hover:cursor-pointer';

        if (addresses !== null && selected !== null && address.id === selected.id) {
            classes += ' bg-primary text-white';
        } else {
            classes += ' hover:bg-gray-100 active:bg-primary-light active:text-white';
        }

        return (
            <AddressPickerInputOptionRender
                className={classes}
                onClick={() => {
                    onItemClick(address);
                }}
                address={address}
                key={address.id}
            />
        );
    });
};

/**
 * Input for select an address from the connected user.
 *
 * @author Nicolas Benoit
 */
export const AddressPickerInput = ({ form, field: { name }, className = '' }) => {
    const [addresses, setAddresses] = useState([]);
    const [currentAddress, setCurrentAddress] = useState(null);

    useEffect(() => {
        backendApi.user.getConnected(accountEmail()).then((response) => {
            if (response.data.addresses.length !== 0) {
                setAddresses(response.data.addresses);
            } else {
                return;
            }

            if (response.data.mainAddress !== undefined) {
                setCurrentAddress(response.data.mainAddress);
            } else {
                setCurrentAddress(response.data.addresses[0]);
            }
        });
    }, []);

    useEffect(() => {
        form.setFieldValue(name, currentAddress === null ? '' : currentAddress.id);
    }, [currentAddress]);

    return (
        <>
            {addresses.length > 0 ? (
                <>
                    <div className={`border rounded-xl p-3 ${className}`}>
                        <AddressPickerInputList
                            addresses={addresses}
                            selected={currentAddress}
                            onItemClick={(address) => {
                                setCurrentAddress(address);
                            }}
                        />
                    </div>
                </>
            ) : (
                <div className="border rounded">
                    <p className="p-2 text-center">
                        Veuillez renseignez au moins une adresse dans votre{' '}
                        <Link className="text-blue-500 underline" to={URL_USER_ADDRESS}>
                            espace utilisateur
                        </Link>
                        .
                    </p>
                </div>
            )}
            <FormFieldErrorMessage name={name} />
        </>
    );
};
