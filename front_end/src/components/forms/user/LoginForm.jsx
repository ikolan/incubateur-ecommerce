import { Field, Form, Formik } from 'formik';
import React from 'react';
import { Link } from 'react-router-dom';

import { initialFormValues } from '../../../constants/initialFormValues';
import { Button } from '../../utils/Button';
import Input from '../../utils/Input';
import { Checkbox } from '../../utils/InputChoices';
import MessageBox from '../../utils/MessageBox';
import { Spinner } from '../../utils/Spinner';
import { URL_USER_REGISTER } from './../../../constants/urls';
import { schemaFormLogin } from './../../../constants/yup-schemas/schemaFormUser';

/**
 * Component Form Login
 * Use Formik to create the Form
 *
 * @param {function} submit: submit Function
 * @param {object} initialValues: the initial values of the form
 * @param {boolean} errorLog: to display or not the message of login/mdp not valid
 * @param {object} validationSchema: validation's schema of the form
 * @param {boolean} loginInProgress Login is in progress
 * @author Peter Mollet
 */
export const LoginForm = ({ onSubmit, errorLog, loginInProgress }) => (
    <Formik
        initialValues={initialFormValues.login}
        onSubmit={onSubmit}
        validationSchema={schemaFormLogin}
    >
        <Form className="mt-8 space-y-6">
            {errorLog && (
                <MessageBox type="error">Email et/ou mot de passe invalide.</MessageBox>
            )}
            <div className="rounded-md shadow-sm -space-y-px">
                <Field
                    type="email"
                    name="email"
                    placeholder="Adresse email"
                    component={Input}
                    className="rounded-none rounded-t-md"
                    noError
                />
                <Field
                    type="password"
                    name="password"
                    placeholder="Mot de passe"
                    component={Input}
                    className="rounded-none rounded-b-md"
                    noError
                />
            </div>

            <div className="flex items-center justify-between">
                <div className="text-sm">
                    <Link to="/forgot-password">
                        <span className="font-medium text-white underline hover:font-bold cursor-pointer">
                            Mot de passe oublié ?
                        </span>
                    </Link>
                </div>
            </div>

            <div>
                {loginInProgress ? (
                    <Spinner legend="Connexion en cours ..." mode="line" lightMode />
                ) : (
                    <>
                        <Button className="w-full" type="submit">
                            Se connecter
                        </Button>
                        <Button
                            className="mt-4 w-full"
                            style="primary-outline"
                            link={URL_USER_REGISTER}
                        >
                            Créer mon compte
                        </Button>
                    </>
                )}
            </div>
        </Form>
    </Formik>
);
