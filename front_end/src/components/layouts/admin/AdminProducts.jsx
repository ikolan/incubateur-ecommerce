import React from 'react';
import { useHistory } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import {
    URL_ADMIN_ADD_PRODUCT,
    URL_ADMIN_EDIT_PRODUCT,
    URL_PRODUCTS_DETAIL,
} from '../../../constants/urls';
import { price } from '../../../services/priceService';
import { ListItemsExplorer } from '../../containers/ListItemsExplorer';
import { Button } from '../../utils/Button';

const AdminProductListRender = ({ item }) => {
    return (
        <>
            <p className="text-xl font-bold">{item.name}</p>
            <p>
                <span className="font-bold">Référence:</span> {item.reference}
            </p>
        </>
    );
};

const AdminProductsFullRender = ({ item, actions }) => {
    return (
        <>
            <div className="px-3 py-1 bg-gray-100">
                <p className="text-xl font-bold">{item.name}</p>
                <p>
                    <span className="font-bold">Référence:</span> {item.reference}
                </p>
                <p>
                    <span className="font-bold">Catégorie:</span>{' '}
                    {item.category !== undefined ? (
                        item.category.label
                    ) : (
                        <span className="italic">Aucune</span>
                    )}
                </p>
                <p>
                    <span className="font-bold">Tags: </span>
                    {item.tags.length !== 0 ? (
                        item.tags.map((tag, index) => {
                            return (
                                <span
                                    key={index}
                                    className="m-1 p-1 text-sm bg-gray-300 rounded-xl"
                                >
                                    {tag.label}
                                </span>
                            );
                        })
                    ) : (
                        <span className="italic">Aucun</span>
                    )}
                </p>
                <p>
                    <span className="font-bold">Prix: </span>
                    {price(item.price)}
                </p>
                <p>
                    <span className="font-bold">Taxe: </span>
                    {String(item.tax / 100).replace('.', ',')} %
                </p>
            </div>
            <div className="m-1">
                <Button className="m-1" onClick={actions.edit}>
                    Editer
                </Button>
                <Button
                    style="red"
                    className="m-1"
                    onClick={actions.delete}
                    doubleCheck="Sûre ?"
                >
                    Supprimer
                </Button>
                <Button
                    style="primary-outline"
                    className="m-1"
                    onClick={actions.goToProductPage}
                >
                    Aller à la page produit
                </Button>
            </div>
        </>
    );
};

export const AdminProducts = () => {
    const history = useHistory();

    return (
        <div className="my-5">
            <Button className="mb-3" link={URL_ADMIN_ADD_PRODUCT}>
                Ajouter un produit
            </Button>
            <ListItemsExplorer
                onLoading={(page, search) => {
                    return backendApi.product.getAll([], page, search);
                }}
                itemListRender={(item) => <AdminProductListRender item={item} />}
                itemFullRender={(item, actions) => (
                    <AdminProductsFullRender item={item} actions={actions} />
                )}
                itemActions={{
                    edit: (item) => {
                        history.push(
                            URL_ADMIN_EDIT_PRODUCT.replace(':reference', item.reference),
                        );
                        return item;
                    },
                    delete: (item) => {
                        backendApi.product.delete(item.reference);
                        return null;
                    },
                    goToProductPage: (item) => {
                        history.push(
                            URL_PRODUCTS_DETAIL.replace(':reference', item.reference),
                        );
                    },
                }}
                emptySelectionMessage="Aucun produit séléctionné"
                search
            />
        </div>
    );
};
