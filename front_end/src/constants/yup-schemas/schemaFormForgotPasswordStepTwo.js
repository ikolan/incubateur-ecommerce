import * as Yup from 'yup';

import {
    REGEX_HAS_LOWER_CASE,
    REGEX_HAS_NUMERIC,
    REGEX_HAS_SPECIAL_CHARACTER,
    REGEX_HAS_UPPER_CASE,
} from '../regex';

export const schemaFormForgetPasswordStepTwo = Yup.object().shape({
    password: Yup.string()
        .min(8, 'Le mot de passe doit étre composé de 8 caractéres minimum.')
        .matches(
            REGEX_HAS_LOWER_CASE,
            'Votre mot de passe doit avoir au moins 1 minuscule.',
        )
        .matches(
            REGEX_HAS_UPPER_CASE,
            'Votre mot de passe doit avoir au moins 1 majuscule.',
        )
        .matches(REGEX_HAS_NUMERIC, 'Votre mot de passe doit avoir au moins 1 chiffre.')
        .matches(
            REGEX_HAS_SPECIAL_CHARACTER,
            'Votre mot de passe doit avoir au moins 1 caractère spéciale.',
        )
        .required('Un mot de passe est obligatoire.'),
    passwordConfirmation: Yup.string()
        .required('La confirmation est obligatoire.')
        .oneOf(
            [Yup.ref('password'), null],
            'La confirmation est differente du mot de passe.',
        ),
});
