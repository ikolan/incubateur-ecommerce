import { Field, Form, Formik } from 'formik';
import React from 'react';

import { initialFormValues } from '../../../constants/initialFormValues';
import { schemaFormAdminAddImage } from '../../../constants/yup-schemas/schemaFormAdminAddImage';
import { Button } from '../../utils/Button';
import { ImageFileChooser } from '../../utils/ImageFileChooser';
import Input from '../../utils/Input';

export const AdminImageForm = ({ onSubmit, ...rest }) => {
    return (
        <Formik
            initialValues={initialFormValues.adminImage}
            validationSchema={schemaFormAdminAddImage}
            onSubmit={onSubmit}
            {...rest}
        >
            <Form>
                <label htmlFor="name">Nom</label>
                <Field name="name" component={Input} />
                <label htmlFor="file">Fichier</label>
                <Field name="file" component={ImageFileChooser} />
                <Button className="my-5" type="submit">
                    Ajouter
                </Button>
            </Form>
        </Formik>
    );
};
