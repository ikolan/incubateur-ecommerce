import * as Yup from 'yup';

export const schemaFormContact = Yup.object().shape({
    firstName: Yup.string().required('Le prénom est obligatoire.'),
    lastName: Yup.string().required('Le nom est obligatoire.'),
    email: Yup.string()
        .email('Veuillez saisir un email valide.')
        .required('Une adresse email est obligatoire.'),
    subject: Yup.string().required('Un sujet est obligatoire.'),
    message: Yup.string().required('Un message est obligatoire.'),
});
