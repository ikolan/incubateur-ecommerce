import { Field, Form, Formik } from 'formik';
import React from 'react';

import { schemaFormContact } from '../../constants/yup-schemas/schemaFormContact';
import { Button } from '../utils/Button';
import Input from '../utils/Input';
import { TextArea } from '../utils/TextArea';

export const ContactForm = ({ initialValues, onSubmit }) => {
    return (
        <Formik
            initialValues={initialValues}
            validationSchema={schemaFormContact}
            onSubmit={onSubmit}
        >
            <Form>
                <div>
                    <label htmlFor="firstName">Prénom et nom</label>
                </div>
                <div className="flex">
                    <div className="flex-1">
                        <Field
                            className="rounded-none rounded-l"
                            name="firstName"
                            type="text"
                            component={Input}
                            placeholder="Prénom"
                        />
                    </div>
                    <div className="flex-1">
                        <Field
                            className="rounded-none rounded-r"
                            name="lastName"
                            type="text"
                            component={Input}
                            placeholder="Nom"
                        />
                    </div>
                </div>

                <div className="mt-4">
                    <label htmlFor="subject">Adresse email</label>
                </div>
                <Field name="email" type="email" component={Input} placeholder="Email" />

                <div className="mt-4">
                    <label htmlFor="subject">Sujet du message</label>
                </div>
                <Field name="subject" type="text" component={Input} placeholder="Sujet" />

                <div className="mt-4">
                    <label htmlFor="subject">Message</label>
                </div>
                <Field
                    name="message"
                    type="text"
                    component={TextArea}
                    placeholder="Message"
                    rows={10}
                />

                <div className="mt-6">
                    <Button className="w-full" type="submit" style="primary">
                        Envoyer le message
                    </Button>
                </div>
            </Form>
        </Formik>
    );
};
