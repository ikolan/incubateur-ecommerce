import { Field, Form, Formik } from 'formik';
import React, { useState } from 'react';

import { schemaFormAdminEditProduct } from '../../../constants/yup-schemas/schemaFormAdminEditProduct';
import { Button } from '../../utils/Button';
import { CategoryInput } from '../../utils/CategoryInput';
import { FloatForIntInput } from '../../utils/FloatForIntInput';
import { ImagePickerInput } from '../../utils/ImagePickerInput';
import Input from '../../utils/Input';
import { Checkbox } from '../../utils/InputChoices';
import { Spinner } from '../../utils/Spinner';
import { TagsInput } from '../../utils/TagsInput';
import { TextArea } from '../../utils/TextArea';

/**
 * Form for adding/editing a product.
 */
export const AdminProductForm = ({
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
            validationSchema={schemaFormAdminEditProduct}
            {...rest}
        >
            <Form>
                <label className="block mt-2" htmlFor="name">
                    Nom du produit
                </label>
                <Field name="name" component={Input} />
                <label className="block mt-2" htmlFor="reference">
                    Référence du produit
                </label>
                <Field name="reference" component={Input} />
                <label className="block mt-2" htmlFor="price">
                    Prix
                </label>
                <Field name="price" component={FloatForIntInput} suffix="€" />
                <label className="block mt-2" htmlFor="tax">
                    Tax
                </label>
                <Field name="tax" component={FloatForIntInput} suffix="%" />
                <label className="block mt-2" htmlFor="stock">
                    En stock
                </label>
                <Field name="stock" component={Input} type="number" min="0" />
                <label className="block mt-2" htmlFor="category">
                    Catégorie
                </label>
                <Field name="category" component={CategoryInput} />
                <label className="block mt-2" htmlFor="tags">
                    Tags
                </label>
                <Field name="tags" component={TagsInput} />
                <label className="block mt-2" htmlFor="description">
                    Description simple
                </label>
                <Field name="description" component={TextArea} />
                <label className="block mt-2" htmlFor="detailedDescription">
                    Description complete
                </label>
                <Field name="detailedDescription" component={TextArea} />
                <label htmlFor="image">Image</label>
                <Field name="image" component={ImagePickerInput} />
                <Field
                    name="frontPage"
                    component={Checkbox}
                    label="Mettre en avant ce produit."
                />

                <Button className="mt-4" type="submit">
                    {submitButtonChildren}
                </Button>
            </Form>
        </Formik>
    );
};
