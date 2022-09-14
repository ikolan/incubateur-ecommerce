import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';

import { backendApi } from '../../api/backend/backendApi';
import { MenuBasedLayout } from '../../components/containers/MenuBasedLayout';
import { AdminEditProduct } from '../../components/layouts/admin/AdminEditProduct';
import { AdminSideMenu } from '../../components/layouts/admin/AdminSideMenu';

export const AdminEditProductView = () => {
    const { reference } = useParams();
    const [isLoading, setIsLoading] = useState(true);
    const [product, setProduct] = useState(null);

    useEffect(() => {
        if (reference !== undefined) {
            backendApi.product
                .getProduct(reference)
                .then((response) => {
                    setProduct(response.data);
                    setIsLoading(false);
                })
                .catch(() => {
                    setIsLoading(false);
                });
        } else {
            setIsLoading(false);
        }
    }, []);

    return (
        <>
            {isLoading ? (
                <></>
            ) : product === null ? (
                <MenuBasedLayout title="Ajouter un produit" menu={<AdminSideMenu />}>
                    <AdminEditProduct />
                </MenuBasedLayout>
            ) : (
                <MenuBasedLayout title="Edition d'un produit" menu={<AdminSideMenu />}>
                    <AdminEditProduct product={product} />
                </MenuBasedLayout>
            )}
        </>
    );
};
