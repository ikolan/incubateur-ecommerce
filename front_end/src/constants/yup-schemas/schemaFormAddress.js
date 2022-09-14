import * as Yup from 'yup';

import { REGEX_PHONE } from '../regex';

export const schemaFormAddress = Yup.object().shape({
    title: Yup.string().required("Un nom d'addresse est requis."),
    number: Yup.number()
        .positive('Le numéro de rue doit étre positif.')
        .required('Un numéro de rue est requis.'),
    road: Yup.string().required('Un nom de rue est requis.'),
    zipcode: Yup.string().required('Un code postal est requis.'),
    city: Yup.string().required('Une ville est requise'),
    phone: Yup.string()
        .required('Un numéro de téléphone de contact est requis.')
        .matches(REGEX_PHONE, 'Veuillez renseignez un numéro de téléphone valide.'),
});
