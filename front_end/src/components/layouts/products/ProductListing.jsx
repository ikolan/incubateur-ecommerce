import React, { useEffect, useState } from 'react';
import { useHistory, useLocation } from 'react-router-dom';

import { backendApi } from '../../../api/backend/backendApi';
import { URL_PRODUCTS_HOME } from '../../../constants/urls';
import ProductFilter from './ProductFilter';
import ProductList from './ProductList';

const ProductListing = () => {
    const history = useHistory();
    const location = useLocation();
    const [products, setProducts] = useState([]);
    const [filters, setFilters] = useState([]);
    const [pages, setPages] = useState({});
    const [fetchTimeout, setFetchTimeout] = useState(null);

    useEffect(() => {
        const page = new URLSearchParams(location.search).get('page');
        pages.current = page === null ? 1 : page;
    }, []);

    useEffect(() => {
        if (fetchTimeout !== null) {
            clearTimeout(fetchTimeout);
        }

        setFetchTimeout(
            setTimeout(() => {
                fetchProducts(filters, pages.current);
            }, 500),
        );
    }, [filters]);

    useEffect(() => {
        const urlParams = new URLSearchParams(location.search);
        if (pages.current === 1) {
            urlParams.delete('page');
        } else {
            urlParams.set('page', pages.current);
        }
        history.push(URL_PRODUCTS_HOME + '?' + urlParams.toString());
        window.scrollTo(0, 0);
    }, [pages.current]);

    // Defines filters for product search
    const onFilter = (input) => {
        if (input.delete) {
            setFilters(filters.filter((filter) => filter.type !== input.type));
        } else if (input.value === null || input.value === '') {
            return;
        } else {
            setFilters([...filters, input]);
        }
    };

    const fetchProducts = (filters, page = 1) => {
        backendApi.product.getAll(filters, page).then((response) => {
            const PagesData = response.data['hydra:view'];
            setProducts(response);
            if (PagesData) {
                setPages({
                    first:
                        PagesData['hydra:first'] == PagesData['@id']
                            ? null
                            : PagesData['hydra:first'].split('page=')[1],
                    previous: PagesData['hydra:previous']
                        ? PagesData['hydra:previous'].split('page=')[1]
                        : null,
                    current:
                        response.data['hydra:member'].length !== 0
                            ? Number(PagesData['@id'].split('page=')[1])
                            : fetchProducts(filters, 1),
                    next: PagesData['hydra:next']
                        ? PagesData['hydra:next'].split('page=')[1]
                        : null,
                    last:
                        PagesData['hydra:last'] == PagesData['@id']
                            ? null
                            : PagesData['hydra:last'].split('page=')[1],
                });
            }
        });
    };

    return (
        <div className="w-full px-4 flex space-x-12">
            <ProductFilter onFilter={onFilter} />
            {products ? (
                <ProductList
                    products={products.data}
                    pages={pages}
                    onPageChange={(page) => {
                        fetchProducts(filters, Number(page));
                    }}
                />
            ) : (
                ''
            )}
        </div>
    );
};

export default ProductListing;
