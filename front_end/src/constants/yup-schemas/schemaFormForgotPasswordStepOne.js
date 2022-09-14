import * as Yup from 'yup';

import { REGEX_EMAIL } from '../regex';

export const schemaFormForgotPasswordStepOne = Yup.object().shape({
    email: Yup.string()
        .required('Votre adresse email est requise.')
        .matches(REGEX_EMAIL, 'Une adresse email valide est requise.'),
});
