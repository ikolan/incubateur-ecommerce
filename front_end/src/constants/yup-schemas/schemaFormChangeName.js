import * as Yup from 'yup';

export const schemaFormChangeName = Yup.object().shape({
    firstName: Yup.string().required('Veuillez entrer un prénom.'),
    lastName: Yup.string().required('Veuillez entrer un nom.'),
});
