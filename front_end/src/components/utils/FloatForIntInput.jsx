import React, { useState } from 'react';

import { FormFieldErrorMessage } from './FormFieldErrorMessage';

const makeDefaultValue = (decimal) => {
    let result = '0,';
    for (let i = 0; i < decimal; i++) {
        result += '0';
    }
    return result;
};

const floatToInt = (input, decimal) => {
    const value =
        Math.max(0, parseFloat(input.split(' ')[0].replace(',', '.'))) *
        Math.pow(10, decimal);
    return Number.isNaN(value) ? 0 : value;
};

const intToFloatDisplay = (value, decimal) => {
    return !Number.isNaN(Number(value))
        ? `${(value / Math.pow(10, decimal)).toFixed(decimal)}`.replace('.', ',')
        : makeDefaultValue(decimal);
};

/**
 * Formik Field Component for choose a decimal value representing by an integer internally.
 * Exemple: 1,99 € is 199 in db.
 *
 * @param decimal Amount of decimal
 * @param suffix (Optional) Symbol or word to put after the value.
 * @author Nicolas Benoit
 */
export const FloatForIntInput = ({
    form,
    field: { name, value },
    decimal = 2,
    suffix = '',
}) => {
    const [displayValue, setDisplayValue] = useState(
        intToFloatDisplay(value, decimal) + ' ' + suffix,
    );

    const handleChange = (event) => {
        setDisplayValue(event.target.value);
    };

    const handleBlur = (event) => {
        const value = floatToInt(event.target.value, decimal);
        setDisplayValue(intToFloatDisplay(value, decimal) + ' ' + suffix);
        form.setFieldValue(name, value);
    };

    return (
        <div className="relative">
            <input
                id={name}
                name={name}
                type="text"
                className={`input ${
                    form.errors[name] && form.touched[name] && 'input-error'
                }`}
                onBlur={handleBlur}
                onChange={handleChange}
                value={displayValue}
            />
            <FormFieldErrorMessage name={name} />
        </div>
    );
};
