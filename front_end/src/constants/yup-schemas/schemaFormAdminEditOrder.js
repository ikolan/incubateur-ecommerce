import * as Yup from 'yup';

export const schemaFormAdminEditOrder = Yup.object().shape({
    status: Yup.number(),
});
