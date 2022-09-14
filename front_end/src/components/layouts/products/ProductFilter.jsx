import React, { useEffect, useRef, useState } from 'react';
import { useHistory, useLocation } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_PRODUCTS_HOME } from '../../../constants/urls';
import { PriceSlider } from '../../utils/PriceSlider';

const ProductFilterBox = ({ children, title }) => {
    return (
        <div className="w-full bg-white drop-shadow-productBox p-8 space-y-4">
            <h4 className='lg:text-2xl xl:text-3xl'>{title.toUpperCase()}</h4>
            {children}
        </div>
    );
};

/**
 * @author NemesisMKII
 */
const ProductFilter = (props) => {
    const location = useLocation();
    const history = useHistory();
    const isMounted = useRef(false);
    const [search, setSearch] = useState('');
    const [searchTimeout, setSearchTimeout] = useState(null);
    const [inStock, setInStock] = useState(false);
    const [categories, setcategories] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState(null);
    const [priceMin, setPriceMin] = useState(null);
    const [priceMax, setPriceMax] = useState(null);
    const [priceSlice, setPriceSlice] = useState([0, 0]);

    const updateUrl = () => {
        let url = new URLSearchParams(location.search);

        if (search !== '') {
            url.set('search', search);
        } else {
            url.delete('search');
        }

        if (inStock) {
            url.set('inStock', 'true');
        } else {
            url.delete('inStock');
        }

        if (selectedCategory !== null) {
            url.set('category', selectedCategory.label);
        } else {
            url.delete('category');
        }

        if (priceMin !== null && priceMax !== null) {
            url.set('price', priceMin * 100 + '-' + priceMax * 100);
        } else {
            url.delete('price');
        }

        history.push(URL_PRODUCTS_HOME + '?' + url.toString());
    };

    useEffect(() => {
        const url = new URLSearchParams(location.search);

        const searchQuery = url.get('search');
        setSearch(searchQuery === null ? '' : searchQuery);

        setInStock(url.get('inStock') === 'true');

        backendApi.category.getAll().then((response) => {
            setcategories(response.data['hydra:member']);
            let categoryQuery = url.get('category');
            if (categoryQuery !== null && response.data['hydra:member'].length > 0) {
                const posibleSelectedCategory = response.data['hydra:member'].filter(
                    (category) => category.label === categoryQuery,
                );
                setSelectedCategory(
                    posibleSelectedCategory.length === 1
                        ? posibleSelectedCategory[0]
                        : null,
                );
            }
        });

        backendApi.product.getPriceSlice().then((response) => {
            const slice = response.data.map((value) => value / 100);
            const priceQuery = url.get('price');
            if (priceQuery !== null) {
                let prices = priceQuery.split('-');
                prices = prices.map((price) => {
                    return isNaN(price) ? slice[prices.indexOf(price)] * 100 : price;
                });

                setPriceMin(Number(prices[0]) / 100);
                setPriceMax(Number(prices[1]) / 100);
            } else {
                setPriceMin(slice[0]);
                setPriceMax(slice[1]);
            }
            setPriceSlice(slice);
        });

        isMounted.current = true;

        return () => {
            isMounted.current = false;
        };
    }, []);

    useEffect(() => {
        if (searchTimeout !== null) {
            clearTimeout(searchTimeout);
            setSearchTimeout(null);
        }
        setSearchTimeout(
            setTimeout(() => {
                setSearchTimeout(null);
                props.onFilter({
                    type: 'name',
                    value: search,
                });
            }, 500),
        );
    }, [search]);

    useEffect(() => {
        props.onFilter({
            type: 'stock',
            value: inStock ? 0 : '',
            delete: !inStock,
        });
    }, [inStock]);

    useEffect(() => {
        if (selectedCategory === null || selectedCategory === undefined) {
            props.onFilter({
                type: 'category',
                delete: true,
            });
        } else {
            props.onFilter({
                type: 'category',
                value: selectedCategory.label,
            });
        }
    }, [selectedCategory]);

    useEffect(() => {
        const url = new URLSearchParams(location.search);
        let searchQuery = url.get('search');
        let categoryQuery = url.get('category');

        if (searchQuery !== null) {
            setSearch(searchQuery);
        }
        if (categoryQuery !== null) {
            const posibleCategory = categories.filter(
                (category) => category.label === categoryQuery,
            );
            setSelectedCategory(posibleCategory.length === 1 ? posibleCategory[0] : null);
        }
    }, [location.search]);

    useEffect(() => {
        if (isMounted.current) {
            updateUrl();
        }
    }, [search, inStock, selectedCategory, priceMin, priceMax]);

    return (
        <div className="w-1/4 h-1/2 flex flex-col space-y-8">
            <ProductFilterBox title="AFFINEZ LA RECHERCHE">
                <p>Recherche par nom de produit</p>
                <input
                    className="lg:w-full xl:w-3/4 lg:text-[0.8rem] xl:text-base"
                    type="text"
                    name="product_name"
                    placeholder="Entrez un nom de produit, un tag..."
                    value={search}
                    onChange={(input) => {
                        setSearch(input.target.value);
                    }}
                />
            </ProductFilterBox>
            <ProductFilterBox title="DISPONIBILITE">
                <div className="flex items-center">
                    <input
                        className="w-6 h-6"
                        type="checkbox"
                        checked={inStock}
                        onChange={(input) => {
                            setInStock(input.target.checked);
                        }}
                    />
                    <p className="lg:text-base xl:text-2xl 2xl:text-3xl">Produits en stock</p>
                </div>
            </ProductFilterBox>
            <ProductFilterBox title="CATEGORIES">
                <div className="flex flex-col">
                    {categories.map((category) => {
                        const classes =
                            category === selectedCategory
                                ? 'bg-primary font-bold text-white'
                                : 'hover:bg-gray-100';
                        return (
                            <p
                                className={`p-2 rounded-md hover:cursor-pointer ${classes}`}
                                key={category.label}
                                onClick={(input) => {
                                    if (category === selectedCategory) {
                                        setSelectedCategory(null);
                                    } else {
                                        setSelectedCategory(category);
                                    }
                                }}
                            >
                                {category.label}
                            </p>
                        );
                    })}
                </div>
            </ProductFilterBox>
            <ProductFilterBox title="TRIER PAR PRIX">
                <div>
                    <PriceSlider
                        value={[priceMin, priceMax]}
                        defaultValue={priceSlice}
                        onAfterChange={(value) => {
                            setPriceMin(value[0]);
                            setPriceMax(value[1]);
                            props.onFilter({
                                type: 'price',
                                value: value[0] * 100 + '-' + value[1] * 100,
                            });
                        }}
                    />
                </div>
            </ProductFilterBox>
        </div>
    );
};

export default ProductFilter;
