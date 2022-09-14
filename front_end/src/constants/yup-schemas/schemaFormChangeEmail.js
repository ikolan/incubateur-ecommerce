import * as Yup from 'yup';

import { REGEX_EMAIL } from '../regex';

export const schemaFormChangeEmail = Yup.object().shape({
    mail: Yup.string()
        .required('Veuillez rensignez une adresse email.')
        .matches(REGEX_EMAIL, 'Veuillez renseignez une adresse email valide.'),
});
