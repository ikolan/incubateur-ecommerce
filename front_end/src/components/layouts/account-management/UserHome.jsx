import React from 'react';

import {
    URL_USER_CHANGE_BIRTHDATE,
    URL_USER_CHANGE_EMAIL,
    URL_USER_CHANGE_NAME,
} from '../../../constants/urls';
import { URL_BACK_USER_ACTIVATION } from '../../../constants/urlsBack';
import { formatDateString } from '../../../services/dateServices';
import PanelMessage from '../../containers/PanelMessage';
import { TitledPanel } from '../../containers/TitledPanel';
import { Button } from '../../utils/Button';
import { Spinner } from '../../utils/Spinner';
import GetUser from '../user/GetUser';
import UserDeactivation from './UserDeactivation';

const UserData = ({ user }) => {
    return (
        <TitledPanel title="Mes informations personnelles">
            <p>
                <span className="font-bold">Nom :</span> {user.data.lastName}
            </p>
            <p>
                <span className="font-bold">Prénom :</span> {user.data.firstName}
            </p>
            <p>
                <span className="font-bold">Adresse email :</span> {user.data.email}
            </p>
            <p>
                <span className="font-bold">Date de naisssance :</span>{' '}
                {formatDateString(user.data.birthDate)}
            </p>
            <hr className="my-2" />
            <Button
                style="primary"
                link={URL_USER_CHANGE_NAME}
                className="w-full md:w-auto mr-1 mb-2 md:mb-1"
            >
                Modifier le prénom et le nom
            </Button>
            <Button
                style="primary"
                link={URL_USER_CHANGE_EMAIL}
                className="w-full md:w-auto mr-1 mb-2 md:mb-1"
            >
                Modifier l'adresse email
            </Button>
            <Button
                style="primary"
                link={URL_USER_CHANGE_BIRTHDATE}
                className="w-full md:w-auto mr-1 mb-2 md:mb-1"
            >
                Modifier la date de naissance
            </Button>
        </TitledPanel>
    );
};

const UserHome = () => {
    const user = GetUser();
    /**
     * MODFIFIER DESACTIVATION DE COMPTE: INTO ENVOI DE MAIL
     */
    if (user) {
        return (
            <div className="w-full h-full p-4 space-y-4">
                {user.data.isActivated == false ? (
                    <PanelMessage
                        data={{
                            color: 'green',
                            message: [
                                "Votre compte n'est pas activé. Pour le faire, vous pouvez cliquer ici: %ACTIVER VOTRE COMPTE",
                                "Vous ne pourrez faire d'achat sur le site sans valider votre compte.",
                            ],
                            link: {
                                type: 'Api',
                                url: URL_BACK_USER_ACTIVATION,
                                params: {
                                    activationKey: user.data.activationKey,
                                },
                            },
                        }}
                        onSuccess={() => {
                            user.data;
                        }}
                    />
                ) : (
                    ''
                )}
                <UserData user={user} />
                {user.data.isActivated == false ? (
                    <UserDeactivation type="activate" />
                ) : (
                    <UserDeactivation type="deactivate" />
                )}
            </div>
        );
    }
    return <Spinner legend="Obtention des infos..." />;
};

export default UserHome;
