import * as Yup from 'yup';

export const schemaFormChangeBirthDate = Yup.object().shape({
    birthDate: Yup.date().required('Une date valide est requise.'),
});
