import React, { useState } from 'react';
import { useDispatch } from 'react-redux';
import { useHistory } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import { signOut } from '../../../redux/authenticationSlice';
import Modal from '../../containers/Modal';
import { TitledPanel } from '../../containers/TitledPanel';
import { Button } from '../../utils/Button';
import { accountEmail } from './../../../services/accountServices';

const titleFragment = ' le compte';

const UserDeactivation = (props) => {
    const dispatch = useDispatch();
    const history = useHistory();
    const [modal, setmodal] = useState(false);

    if (props.type == 'activate') {
        var content = {
            title: 'Activer' + titleFragment,
        };
        var modalContent = {
            title: 'Activer' + titleFragment,
            content: `En cliquant sur confirmer, vous recevrez un mail vous invitant à cliquer sur le lien afin de réactiver votre compte.`,
            button: 'Confirmer',
        };
    } else {
        var content = {
            title: 'Désactiver' + titleFragment,
        };
        var modalContent = {
            title: 'Désactiver' + titleFragment,
            content: `ATTENTION: en cliquant sur confirmer, votre compte sera désactivé
            et vous serez déconnecté. Vous pourrez toujours vous connecter
            mais il faudra réactiver le compte avant de pouvoir faire des
            achats sur le site.`,
            button: 'Confirmer',
        };
    }

    const onActionSuccess = () => {
        setmodal(false);
        dispatch(signOut());
        history.push('login');
    };

    const onModalCancel = () => {
        setmodal(false);
    };

    const onActionError = () => {
        alert('Une erreur est survenue.');
    };

    const onActivationSubmit = () => {
        backendApi.user.reactivationRequest(accountEmail());
        setmodal(false);
    };

    const onDeactivationSubmit = () => {
        backendApi.user.deactivate().then(onActionSuccess).catch(onActionError);
    };

    return (
        <TitledPanel title={content.title}>
            {props.type == 'activate' ? (
                ''
            ) : (
                <p>
                    Si vous ne souhaitez plus faire d'achat sur le site, vous pouvez
                    choisir de désactiver votre compte afin de ne plus recevoir d'email de
                    notre part.
                </p>
            )}
            <hr className="my-2" />
            <Button
                style="red"
                onClick={() => {
                    setmodal(true);
                }}
                className="w-full md:w-auto"
            >
                {content.title}
            </Button>
            {modal == true ? (
                props.type == 'activate' ? (
                    <Modal
                        content={modalContent}
                        buttons={[
                            {
                                content: 'Annuler',
                                style: 'red',
                                onClick: onModalCancel,
                            },
                            {
                                content: 'Confirmer',
                                style: 'primary',
                                onClick: onActivationSubmit,
                            },
                        ]}
                    >
                        {modalContent.content}
                    </Modal>
                ) : (
                    <Modal
                        content={modalContent}
                        buttons={[
                            {
                                content: 'Annuler',
                                style: 'red',
                                onClick: onModalCancel,
                            },
                            {
                                content: 'Confirmer',
                                style: 'primary',
                                onClick: onDeactivationSubmit,
                            },
                        ]}
                    >
                        {modalContent.content}
                    </Modal>
                )
            ) : (
                ''
            )}
        </TitledPanel>
    );
};

export default UserDeactivation;
