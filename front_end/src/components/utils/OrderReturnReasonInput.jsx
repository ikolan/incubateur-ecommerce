import React, { useEffect } from 'react';

const reasons = [
    'Le produit ne correspond pas à la description',
    'Le produit ne correspond pas aux attentes',
    'Le produit est défectueux ou endommagé',
    'Autre',
];

export const OrderReturnReasonInput = ({ form, field: { name } }) => {
    const handleChange = (event) => {
        form.setFieldValue(name, event.target.value);
    };

    useEffect(() => {
        form.setFieldValue(name, reasons[0]);
    }, []);

    return (
        <select className="input" onChange={handleChange}>
            {reasons.map((reason) => {
                return (
                    <option value={reason} key={reason}>
                        {reason}
                    </option>
                );
            })}
        </select>
    );
};
