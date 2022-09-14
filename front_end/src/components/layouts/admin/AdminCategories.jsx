import React from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { SmallListItemExplorer } from '../../containers/SmallListItemExplorer';

export const AdminCategories = () => {
    return (
        <div className="my-5">
            <SmallListItemExplorer
                onLoading={() => {
                    return backendApi.category.getAll();
                }}
                onAdding={(item) => {
                    return backendApi.category.post(item);
                }}
                onDelete={(item) => {
                    return backendApi.category.delete(item.id);
                }}
                onEdit={(item) => {
                    return backendApi.category.patch(item.id, item.label);
                }}
            />
        </div>
    );
};
