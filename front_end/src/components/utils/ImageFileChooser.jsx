import imageCompression from 'browser-image-compression';
import React, { useState } from 'react';

import { FormFieldErrorMessage } from './FormFieldErrorMessage';
import { Spinner } from './Spinner';

export const ImageFileChooser = ({ form, field: { name }, ...rest }) => {
    const [isLoading, setIsLoading] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');

    const onChange = (event) => {
        if (!['image/png', 'image/jpeg'].includes(event.target.files[0].type)) {
            setErrorMessage('Le fichier doit étre une image au format PNG ou JPEG.');
            return;
        }

        form.setFieldValue(name, '');
        setIsLoading(true);
        errorMessage !== '' && setErrorMessage('');
        imageCompression(event.target.files[0], {
            maxSizeMB: 1,
            maxWidthOrHeight: 1920,
        }).then((file) => {
            let reader = new FileReader();
            reader.readAsArrayBuffer(file);
            reader.onload = () => {
                form.setFieldValue(name, reader.result);
                setIsLoading(false);
            };
        });
    };

    return (
        <div className="relative">
            <div className="rounded-md border border-gray-300 p-2">
                {isLoading && <Spinner legend="Chargement du fichier..." mode="line" />}
                <input
                    id={name}
                    name={name}
                    type="file"
                    accept="image/jpeg,image/png"
                    onChange={onChange}
                    disabled={isLoading}
                    {...rest}
                />
                {errorMessage !== '' && (
                    <p className="text-red-600 font-bold">{errorMessage}</p>
                )}
                <FormFieldErrorMessage name={name} />
            </div>
        </div>
    );
};
