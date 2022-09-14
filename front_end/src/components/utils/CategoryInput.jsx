import React, { useEffect, useState } from 'react';

import { backendApi } from '../../api/backend/backendApi';

/**
 * Formik Field component for selecting a category.
 *
 * @author Nicolas Benoit
 */
export const CategoryInput = ({ form, field: { name, value } }) => {
    const [categories, setCategories] = useState([]);

    useEffect(() => {
        backendApi.category.getAll().then((response) => {
            setCategories(
                response.data['hydra:member'].map((category) => {
                    return {
                        id: category.id,
                        label: category.label,
                    };
                }),
            );
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
            {categories.map((category) => (
                <option value={category.id} key={category.id}>
                    {category.label}
                </option>
            ))}
        </select>
    );
};
