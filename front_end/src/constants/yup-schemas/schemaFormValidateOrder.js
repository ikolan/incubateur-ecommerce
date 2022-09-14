import * as Yup from 'yup';

export const schemaFormValidateOrder = Yup.object().shape({
    address: Yup.string().required('Une adresse est obligatoire'),
});
