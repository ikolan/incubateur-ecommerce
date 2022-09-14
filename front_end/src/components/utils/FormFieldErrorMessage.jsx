import { ErrorMessage } from 'formik';
import React from 'react';

/**
 * Error message under fields.
 *
 * @author Nicolas Benoit
 */
export const FormFieldErrorMessage = ({ name, followColumn = false }) => {
    let classes = 'text-sm text-red-500 font-bold p-1';
    if (followColumn) {
        classes += ' border-x border-t border-red-500 bg-red-50';
    }
    return (
        <>
            <ErrorMessage name={name} className={classes} component="div" />
        </>
    );
};
