import React, { useEffect, useState } from 'react';

import { backendApi } from '../../api/backend/backendApi';
import { BackendImage } from './BackendImage';

const ImagePickerInputBox = ({ image, onClick, selected }) => {
    let classes = 'm-2 border-2 rounded ';
    classes += selected
        ? 'bg-blue-300 border-blue-300 hover:bg-blue-200 hover:border-blue-200 shadow-xl shadow-blue-200'
        : 'bg-gray-200 border-gray-200 hover:bg-gray-300 hover:border-gray-300 shadow';

    return (
        <div
            className={`m-2 border-2 rounded ${classes}`}
            onClick={onClick}
            role="button"
        >
            <p className="text-sm">{image.name}</p>
            <BackendImage
                className="max-h-[96px] rounded"
                id={image.id}
                alt={image.name}
            />
        </div>
    );
};

export const ImagePickerInput = ({ form, field: { name, value } }) => {
    const [images, setImage] = useState([]);
    const [selectedImage, setSelectedImage] = useState(value === undefined ? {} : value);
    const [searchTimeout, setSearchTimeout] = useState(null);

    const clearSearchTimeout = () => {
        if (searchTimeout !== null) {
            clearTimeout(searchTimeout);
            setSearchTimeout(null);
        }
    };

    useEffect(() => {
        backendApi.image.getAll().then((response) => {
            setImage(response.data['hydra:member']);
        });
    }, []);

    return (
        <div className="rounded-md border">
            <div className="p-1">
                <input
                    type="text"
                    className="input"
                    placeholder="Rechercher une image"
                    onChange={(event) => {
                        clearSearchTimeout();
                        setSearchTimeout(
                            setTimeout(() => {
                                clearSearchTimeout();
                                backendApi.image
                                    .getSearch(1, event.target.value)
                                    .then((response) => {
                                        setImage(response.data['hydra:member']);
                                    });
                            }, 500),
                        );
                    }}
                />
            </div>
            <div className="flex flex-wrap justify-center max-h-[400px] overflow-y-auto">
                {images.length > 0 ? (
                    images.map((image) => {
                        return (
                            <ImagePickerInputBox
                                image={image}
                                key={image.id}
                                onClick={() => {
                                    image['@id'] === selectedImage['@id']
                                        ? setSelectedImage({})
                                        : setSelectedImage(image);
                                    form.setFieldValue(name, image);
                                }}
                                selected={image['@id'] === selectedImage['@id']}
                            />
                        );
                    })
                ) : (
                    <div className="m-5">
                        <p className="text-xl">Aucun résultat</p>
                    </div>
                )}
            </div>
        </div>
    );
};
