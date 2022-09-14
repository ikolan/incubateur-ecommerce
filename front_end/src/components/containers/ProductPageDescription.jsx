import React from 'react';

/**
 * @author NemesisMKII
 */
const ProductPageDescription = (props) => {
    return (
        <div className="w-full">
            <h3 className="text-blue-800 text-center uppercase">Qui somme-nous ?</h3>
            <div className="my-24 text-2xl text-center leading-10">
                Depuis sa création en 2005,{' '}
                <span className="text-primary font-bold">Craft Computing</span> s'appuie
                sur des spécialistes pour conceptualiser et créer des{' '}
                <span className="font-medium">PC de bureau</span> permettant de satisfaire
                tous les besoins : PC gamer et polyvalents, professionnels ou bêtes de
                concours. Parmis leurs points communs,{' '}
                <span className="font-medium">choix intelligent de composants PC</span>.
                Cela se concrétise par un{' '}
                <span className="font-medium">l'exigence de la qualité</span>,
                sélectionnés parmi des marques reconnues pour leur excellent rapprt
                performance/prix. Avec un PC{' '}
                <span className="text-primary font-bold">Craft Computing</span>, vous
                faites le choix d'un PC bien équilibré, monté et testé avec soin en
                France. Un protocole de qualité vous assure la livraison d'un ordinateur
                de bureau prêt à fonctionner. Le soin de votre colis est bien sûr une
                priorité, c'est pourquoi votre PC est accompagné d'un suremballage
                ultra-résistant.
            </div>
        </div>
    );
};

export default ProductPageDescription;
