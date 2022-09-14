import React from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_ADMIN_ADD_IMAGE } from '../../../constants/urls';
import { ListItemsExplorer } from '../../containers/ListItemsExplorer';
import { BackendImage } from '../../utils/BackendImage';
import { Button } from '../../utils/Button';

const AdminImageListRender = (item) => {
    return (
        <div className="flex items-center">
            <div className="pr-2 border-r-2">
                <BackendImage
                    id={item.id}
                    alt={item.name}
                    className="h-[64px] rounded shadow"
                />
            </div>
            <p className="text-xl font-bold ml-3">{item.name}</p>
        </div>
    );
};

const AdminImageFullRender = (item, actions) => {
    return (
        <>
            <div className="flex justify-between items-center px-3 py-1 bg-gray-100">
                <p>{item.name}</p>
                <Button style="red" onClick={actions['delete']} doubleCheck="Sûre ?">
                    Supprimer
                </Button>
            </div>
            <div className="p-5">
                <BackendImage
                    id={item.id}
                    alt={item.name}
                    className="w-full max-w-[750px] rounded-2xl shadow"
                />
            </div>
        </>
    );
};

export const AdminImages = () => {
    return (
        <div className="my-5">
            <Button className="mb-3" link={URL_ADMIN_ADD_IMAGE}>
                Ajouter une image
            </Button>
            <ListItemsExplorer
                onLoading={(page, search) => {
                    return search === ''
                        ? backendApi.image.getAll(page)
                        : backendApi.image.getSearch(page, search);
                }}
                itemActions={{
                    delete: (item) => {
                        backendApi.image.delete(item.id);
                        return null;
                    },
                }}
                itemListRender={AdminImageListRender}
                itemFullRender={AdminImageFullRender}
                emptySelectionMessage="Aucune image séléctionné"
                search
            />
        </div>
    );
};
