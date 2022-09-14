import React from 'react';
import { useHistory } from 'react-router-dom/cjs/react-router-dom.min';

import { URL_CONTACT } from '../../constants/urls';
import { Button } from '../utils/Button';
import icon_lock from './../../assets/icons/lock.svg';
import icon_package from './../../assets/icons/package.svg';
import icon_truck from './../../assets/icons/truck.svg';
import liv_chronopost from './../../assets/images/liv_chronopost.png';
import liv_mondial_relay from './../../assets/images/liv_mondial_relay.png';
import liv_shop from './../../assets/images/liv_shop.png';
import liv_ups from './../../assets/images/liv_ups.png';
import logo from './../../assets/images/logo.png';
import payment_cb from './../../assets/images/payment_cb.png';
import payment_master_card from './../../assets/images/payment_master_card.png';
import payment_paypal from './../../assets/images/payment_paypal.png';
import payment_visa from './../../assets/images/payment_visa.png';
import social_facebook from './../../assets/images/social_facebook.png';
import social_instagram from './../../assets/images/social_instagram.png';
import social_linkedin from './../../assets/images/social_linkedin.png';

const FooterLinkGroup = ({ children, title }) => {
    return (
        <div className="flex-1 py-5 md:p-10">
            <p className="font-bold text-center md:text-left">{title.toUpperCase()}</p>
            {children}
        </div>
    );
};

const FooterLinkGroupItem = ({ children, link }) => {
    const history = useHistory();

    return (
        <div>
            <Button
                className="my-2 md:my-4 w-full text-center md:text-left"
                style="link"
                onClick={() => {
                    if (link !== null) {
                        history.push(link);
                    }
                }}
            >
                {children}
            </Button>
        </div>
    );
};

const FooterImagesDisplayer = ({ className = '', title, images }) => {
    const imagesElements = images.map((image) => {
        return <img className="px-2" src={image} alt={image} key={image} />;
    });

    return (
        <div className={'p-5 font-bold text-center ' + className}>
            <p>{title.toUpperCase()}</p>
            <div className="flex justify-center items-center flex-wrap mt-4">
                {imagesElements}
            </div>
        </div>
    );
};

const FooterIconAndDescription = ({ icon, lineOne, lineTwo }) => {
    return (
        <div className="flex flex-col md:flex-row items-center text-center md:text-left">
            <img src={icon} alt={icon} />
            <div className="m-8 md:m-4">
                <p>{lineOne.toUpperCase()}</p>
                <p className="text-primary font-bold">{lineTwo.toUpperCase()}</p>
            </div>
        </div>
    );
};

export const Footer = () => {
    return (
        <>
            <div className="bg-[#E3E3E3] flex justify-evenly items-center py-10">
                <FooterIconAndDescription
                    icon={icon_package}
                    lineOne="Débit uniquement"
                    lineTwo="à l'expédition"
                />
                <FooterIconAndDescription
                    icon={icon_lock}
                    lineOne="Paiment"
                    lineTwo="sécurisé"
                />
                <FooterIconAndDescription
                    icon={icon_truck}
                    lineOne="Livraison rapide"
                    lineTwo="24H / 48H"
                />
            </div>
            <div className="bg-secondary text-white">
                <div className="md:flex justify-around">
                    <FooterImagesDisplayer
                        title="Moyens de paiments"
                        images={[
                            payment_cb,
                            payment_master_card,
                            payment_visa,
                            payment_paypal,
                        ]}
                    />
                    <FooterImagesDisplayer
                        title="Nos partenaires de livraisons"
                        images={[liv_ups, liv_chronopost, liv_mondial_relay, liv_shop]}
                    />
                </div>
                <div className="md:flex items-center">
                    <div className="flex-1 px-32 py-5 md:p-10">
                        <img src={logo} alt="Craft Computing Logo" />
                    </div>
                    <FooterLinkGroup title="Qui somme nous ?">
                        <FooterLinkGroupItem>Qui somme-nous ?</FooterLinkGroupItem>
                        <FooterLinkGroupItem>Nos services</FooterLinkGroupItem>
                        <FooterLinkGroupItem>Nos valeurs</FooterLinkGroupItem>
                        <FooterLinkGroupItem link={URL_CONTACT}>
                            Contactez-nous
                        </FooterLinkGroupItem>
                    </FooterLinkGroup>
                    <FooterLinkGroup title="Informations">
                        <FooterLinkGroupItem>
                            Condition générales de vente
                        </FooterLinkGroupItem>
                        <FooterLinkGroupItem>
                            Données personnelles et cookie
                        </FooterLinkGroupItem>
                        <FooterLinkGroupItem>Rubrique d'aide / FAQ</FooterLinkGroupItem>
                        <FooterLinkGroupItem>
                            Paiement, livraison, SAV
                        </FooterLinkGroupItem>
                    </FooterLinkGroup>
                    <FooterImagesDisplayer
                        className="flex-1"
                        title="Suivez-nous !"
                        images={[social_facebook, social_instagram, social_linkedin]}
                    />
                </div>
                <div className="p-4">
                    <p className="text-center">
                        ©2022 - Tous droits réservés CRAFT COMPUTING
                    </p>
                </div>
            </div>
        </>
    );
};
