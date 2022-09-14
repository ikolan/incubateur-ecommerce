import * as Yup from 'yup';

export const schemaFormLogin = Yup.object().shape({
    email: Yup.string().required('Required input'),
    password: Yup.string().required('Required input'),
});
