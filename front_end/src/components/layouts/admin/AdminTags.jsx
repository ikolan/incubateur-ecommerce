import React from 'react';

import { backendApi } from '../../../api/backend/backendApi';
import { SmallListItemExplorer } from '../../containers/SmallListItemExplorer';

export const AdminTags = () => {
    return (
        <div className="my-5">
            <SmallListItemExplorer
                onLoading={() => {
                    return backendApi.tag.getAll();
                }}
                onAdding={(item) => {
                    return backendApi.tag.post(item);
                }}
                onDelete={(item) => {
                    return backendApi.tag.delete(item.id);
                }}
                onEdit={(item) => {
                    return backendApi.tag.patch(item.id, item.label);
                }}
            />
        </div>
    );
};
