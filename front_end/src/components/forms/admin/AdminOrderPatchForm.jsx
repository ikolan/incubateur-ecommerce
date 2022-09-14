import { Field, Form, Formik } from 'formik';
import React, { useState } from 'react';

import { schemaFormAdminEditOrder } from '../../../constants/yup-schemas/schemaFormAdminEditOrder';
import { Button } from '../../utils/Button';
import { CategoryInput } from '../../utils/CategoryInput';
import { FloatForIntInput } from '../../utils/FloatForIntInput';
import { ImagePickerInput } from '../../utils/ImagePickerInput';
import Input from '../../utils/Input';
import { Checkbox } from '../../utils/InputChoices';
import { Spinner } from '../../utils/Spinner';
import { StatusInput } from '../../utils/StatusInput';
import { TagsInput } from '../../utils/TagsInput';
import { TextArea } from '../../utils/TextArea';

/**
 * Form for adding/editing a product.
 */
export const AdminOrderPatchForm = ({
    initialValues,
    onSubmit,
    submitButtonChildren,
    ...rest
}) => {
    const [isSubmitting, setIsSubmitting] = useState(false);

    return isSubmitting ? (
        <div>
            <Spinner legend="Envoie en cours..." />
        </div>
    ) : (
        <Formik
            initialValues={initialValues}
            onSubmit={(values) => {
                setIsSubmitting(true);
                onSubmit(values);
            }}
            validationSchema={schemaFormAdminEditOrder}
            {...rest}
        >
            <Form>
                <label className="block mt-2" htmlFor="name">
                    Nouveau Statut:
                </label>
                <Field name="status" component={StatusInput} />

                <Button className="mt-4" type="submit">
                    {submitButtonChildren}
                </Button>
            </Form>
        </Formik>
    );
};
