import { ErrorMessage } from 'formik';
import React from 'react';

import ErrorMessSmall from './ErrorMessSmall';
import { FormFieldErrorMessage } from './FormFieldErrorMessage';

/**
 * Composant générique permettant de gérer facilement les Input simple (text, text-area, number) en utilisant MDB et Formik
 *
 * @param {String} placeholder: label de l'input, qui sera affiché
 * @param {Boolean} errorRight: Permet de mettre le message d'erreur sur la droite (par défaut étant à gauche)
 *
 * @example <Field type="email" name="email" placeholder="Email" component={ InsyInput } className='my-0'/>
 * @author Peter Mollet
 */
const Input = ({
    noError,
    className,
    type,
    field: { name },
    field,
    form: { errors, touched },
    followColumn = false,
    ...rest
}) => {
    return (
        <div className="relative">
            <input
                id={name}
                name={name}
                type={type}
                className={`input ${
                    errors[name] && touched[name] && 'input-error'
                } ${className} `}
                {...field}
                {...rest}
            />
            {!noError && (
                <FormFieldErrorMessage name={name} followColumn={followColumn} />
            )}
        </div>
    );
};

export default Input;
