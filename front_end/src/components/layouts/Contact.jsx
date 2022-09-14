import React, { useState } from 'react';

import icon_mail from '../../assets/icons/mail.svg';
import icon_phone from '../../assets/icons/phone.svg';
import { initialFormValues } from '../../constants/initialFormValues';
import { ContactForm } from '../forms/ContactForm';
import MessageBox from '../utils/MessageBox';
import { Spinner } from '../utils/Spinner';
import { backendApi } from './../../api/backend/backendApi';

const ContactBox = ({ children }) => {
    children = children.map((elem, i) => {
        return (
            <div className="my-6" key={i}>
                {elem}
            </div>
        );
    });

    return (
        <div className="max-w-[500px] rounded bg-secondary text-center text-white p-[30px]">
            {children}
        </div>
    );
};

const ContactEmailDisplayer = ({ description, email }) => {
    const Shape = ({ className, color }) => {
        return (
            <div className={'h-full w-[60px] ' + className}>
                <svg width="60" height="117">
                    <polygon points="0,0 59,59 0,116" style={{ fill: color }} />
                </svg>
            </div>
        );
    };

    return (
        <>
            <div className="rounded-r border-2 border-secondary border-l-white w-full md:flex items-center h-[120px] my-5 hidden">
                <Shape className="bg-secondary" color="white" />
                <div className="bg-secondary h-full flex items-center">
                    <h4 className="text-white text-center px-6">
                        {description.toUpperCase()}
                    </h4>
                </div>
                <Shape className="bg-white" color="#0d195a" />
                <div className="px-5">
                    <p className="text-2xl">{email}</p>
                </div>
            </div>
            <div className="text-center border-2 border-secondary my-5 rounded md:hidden">
                <div className="bg-secondary text-white p-5">
                    <h4>{description.toUpperCase()}</h4>
                </div>
                <div className="p-5">
                    <p className="text-2xl">{email}</p>
                </div>
            </div>
        </>
    );
};

export const Contact = () => {
    const SENDING_STATUS = {
        NOT_SEND: 0,
        SENDING_IN_PROGRESS: 1,
        SENDED: 2,
    };

    const [sendingStatus, setSendingStatus] = useState(SENDING_STATUS.NOT_SEND);

    const handleSendingContact = (values) => {
        setSendingStatus(SENDING_STATUS.SENDING_IN_PROGRESS);
        backendApi.contact.post(values).then(() => {
            setSendingStatus(SENDING_STATUS.SENDED);
        });
    };

    return (
        <>
            <div className="flex flex-col justify-center items-center">
                <div className="mt-4 md:max-w-[1000px]">
                    <h2 className=" text-center text-secondary uppercase">
                        Nous contacter
                    </h2>
                    <p className="mt-8">
                        Vous souhaitez obtenir des informations sur un produit en
                        particulier, sur une compatibilité de matériel ? Vous avez une
                        commande en cours et voulez la modifier ? Une demande de retour à
                        effectuer ?
                    </p>
                    <p className="mt-8">
                        Vous pouvez nous contacter par mail ou par téléphone, pensez
                        également à consulter notre Foire Aux Questions, vous y trouverez
                        des réponses à vos interrogations !
                    </p>

                    <div className="flex flex-col justify-center items-center my-20">
                        <img className="mb-20" src={icon_phone} alt={icon_phone} />
                        <ContactBox>
                            <p>Pour nous joindre</p>
                            <p>01 23 45 67 89</p>
                            <p>(numéro non surtaxé)</p>
                            <p>
                                Nos conseillers sont à votre disposition du lundi au
                                vendredi de 9h à 18h.
                            </p>
                        </ContactBox>
                    </div>

                    <div className="flex justify-center">
                        <img className="mb-20" src={icon_mail} alt={icon_mail} />
                    </div>

                    <ContactEmailDisplayer
                        description="Service clientèle"
                        email="info@craftcomputing.net"
                    />

                    <p className="my-10">
                        L'équipe de notre service clientèle est à votre disposition pour
                        répondre à vos éventuelles questions sur une commande en cours, un
                        avoir, un problème de suivi colis ou de livraison, et toute autre
                        information pratique.
                    </p>

                    <ContactEmailDisplayer
                        description="Service après-vente"
                        email="sav@craftcomputing.net"
                    />

                    <p className="my-10">
                        Pour une question sur le fonctionnement d'un produit ou un suivi
                        de dossier SAV, nos techniciens sont à votre service. Toutes les
                        demandes de retour (panne, rétractation, etc.) sont à réaliser via
                        la page suivante : Demande de retour.
                    </p>

                    <ContactEmailDisplayer
                        description="Service technique"
                        email="technique@craftcomputing.net"
                    />

                    <p className="my-10">
                        Vous souhaitez être conseillé pour votre achat, être renseigné sur
                        un produit ou une compatibilité ? Nos techniciens sont à votre
                        écoute et sauront répondre à vos problématiques.
                    </p>

                    <div className="bg-secondary text-white rounded w-full p-4">
                        <h3 className="uppercase text-center">Une question ?</h3>
                        <hr className="border-primary" />
                        <div className="py-8 md:py-16 md:px-32">
                            {sendingStatus === SENDING_STATUS.SENDING_IN_PROGRESS ? (
                                <Spinner legend="Envoi en cours..." lightMode />
                            ) : sendingStatus === SENDING_STATUS.SENDED ? (
                                <MessageBox type="success">
                                    Votre message a été envoyer.
                                </MessageBox>
                            ) : (
                                <ContactForm
                                    initialValues={initialFormValues.contact}
                                    onSubmit={handleSendingContact}
                                />
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};
