import * as Yup from 'yup';

export const schemaFormAdminAddImage = Yup.object().shape({
    name: Yup.string().required('Un nom est obligatoire.'),
    file: Yup.string().required('Un fichier est obligatoire.'),
});
