import React from 'react';

import { FormFieldErrorMessage } from './FormFieldErrorMessage';

export const TextArea = ({
    className,
    type,
    field: { name },
    field,
    form: { errors, touched },
    ...rest
}) => {
    return (
        <div>
            <textarea
                id={name}
                name={name}
                type={type}
                className={`input ${
                    errors[name] && touched[name] && 'input-error'
                } ${className} `}
                {...field}
                {...rest}
            ></textarea>
            <FormFieldErrorMessage name={name} />
        </div>
    );
};
