import * as Yup from 'yup';

import { REGEX_HAS_NUMERIC } from '../regex';

export const schemaFormAddToCart = Yup.object().shape({
    quantity: Yup.string().matches(REGEX_HAS_NUMERIC, 'Veuillez rentrer un nombre.'),
});
