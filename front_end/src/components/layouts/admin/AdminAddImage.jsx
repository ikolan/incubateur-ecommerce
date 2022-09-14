import React, { useState } from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { AdminImageForm } from '../../forms/admin/AdminImageForm';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';

export const AdminAddImage = () => {
    const [isSending, setIsSending] = useState(false);
    const [message, setMessage] = useState([]);

    const handleSubmit = (values) => {
        setIsSending(true);
        backendApi.image.post(
            values.name,
            values.file,
            () => {
                setIsSending(false);
                setMessage(['success', 'Votre image a bien été ajouter.']);
            },
            (error) => {
                setIsSending(false);
                if (error.response.status === 422) {
                    setMessage(['error', 'Ce nom est déja utilisé.']);
                }
            },
        );
    };

    return (
        <div className="my-5">
            {isSending ? (
                <Spinner legend="Ajout en cours..." />
            ) : (
                <>
                    {message.length === 2 && (
                        <MessageBox type={message[0]} className="mb-2">
                            {message[1]}
                        </MessageBox>
                    )}
                    <AdminImageForm onSubmit={handleSubmit} />
                </>
            )}
        </div>
    );
};
