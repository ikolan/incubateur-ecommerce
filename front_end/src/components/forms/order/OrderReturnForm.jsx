import { Field, Form, Formik } from 'formik';
import React from 'react';

import { initialFormValues } from '../../../constants/initialFormValues';
import { Button } from '../../utils/Button';
import { OrderReturnReasonInput } from '../../utils/OrderReturnReasonInput';
import { TextArea } from '../../utils/TextArea';

export const OrderReturnForm = ({ onSubmit = () => {} }) => {
    return (
        <Formik initialValues={initialFormValues.orderReturn} onSubmit={onSubmit}>
            <Form>
                <div>
                    <p>Raison du retour</p>
                    <Field name="reason" component={OrderReturnReasonInput} />
                </div>
                <div>
                    <p>Description (Optionnel)</p>
                    <Field name="description" component={TextArea} />
                </div>
                <Button className="mt-2" type="submit">
                    Envoyer une demande
                </Button>
            </Form>
        </Formik>
    );
};
