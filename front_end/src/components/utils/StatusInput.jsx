import React, { useEffect, useState } from 'react';

import { backendApi } from '../../api/backend/backendApi';

/**
 * Formik Field component for selecting a status.
 *
 * @author Nicolas Benoit
 */
export const StatusInput = ({ form, field: { name, value } }) => {
    const [statuses, setStatuses] = useState([]);

    useEffect(() => {
        backendApi.status.getAll().then((data) => {
            setStatuses(data.data['hydra:member']);
        });
    }, []);

    return (
        <select
            className="input"
            onChange={(event) => {
                form.setFieldValue(name, parseInt(event.target.value));
            }}
            value={value == null ? -1 : value}
        >
            <option value={null}>AUCUNE</option>
            {statuses.map((status) => (
                <option value={status.id} key={status.id}>
                    {status.label}
                </option>
            ))}
        </select>
    );
};
