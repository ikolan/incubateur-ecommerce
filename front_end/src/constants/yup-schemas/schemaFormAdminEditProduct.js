import * as Yup from 'yup';

export const schemaFormAdminEditProduct = Yup.object().shape({
    name: Yup.string().required('Un nom est requis.'),
    reference: Yup.string().required('Une référence est requise.'),
    price: Yup.number()
        .required('Un prix est requis.')
        .min(0, 'Le prix doit étre positif.'),
    tax: Yup.number()
        .required('Une taxe est requise.')
        .min(0, 'La taxe doit étre positif.'),
    description: Yup.string().required('Une description est requise.'),
    detailedDescription: Yup.string().required('Une description est requise.'),
    weight: Yup.number(),
    stock: Yup.number().min(0, 'Le nombre de produit en stock doit étre positif.'),
    frontPage: Yup.bool(),
    category: Yup.number().nullable(),
    tags: Yup.array().of(Yup.number()),
});
