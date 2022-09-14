import { Field, Form, Formik } from 'formik';
import React, { useEffect, useState } from 'react';

import searchIcon from '../../assets/icons/search.svg';
import { Button } from '../utils/Button';
import Input from '../utils/Input';
import { PageChanger } from '../utils/PageChanger';
import { Spinner } from '../utils/Spinner';

/**
 * Search Form
 */
const ListItemExplorerSearchForm = ({ onSubmit, initialValue }) => {
    return (
        <Formik
            initialValues={{
                search: initialValue,
            }}
            onSubmit={onSubmit}
        >
            <Form className="flex items-center">
                <Field
                    className="m-1 rounded-none rounded-l-xl border-r-0 h-[28px]"
                    type="text"
                    name="search"
                    id="search"
                    component={Input}
                />
                <Button
                    className="m-1 rounded-l-none rounded-r-xl h-[28px] text-sm"
                    type="submit"
                >
                    Recherche
                </Button>
            </Form>
        </Formik>
    );
};

/**
 * Button for the page changer.
 */
const ListItemsExplorerPageChangerButton = ({ children, page, selected, onClick }) => {
    return selected ? (
        <div className="px-3 py-1 bg-gray-300 font-bold" key={children}>
            <p>{children}</p>
        </div>
    ) : (
        <Button
            style="none"
            className="px-3 py-1 hover:bg-gray-200 active:bg-gray-300"
            onClick={() => onClick(page)}
            key={page}
        >
            {children}
        </Button>
    );
};

/**
 * Page indicator of the list.
 */
const ListItemsExplorerPageChanger = ({
    totalPages,
    currentPage,
    currentSearch,
    onClick,
    onSearch = null,
}) => {
    const [searchMode, setSearchMode] = useState(false);

    return (
        <div className="flex bg-gray-100 border rounded-t h-[32px]">
            <div className="flex justify-center w-full">
                {searchMode ? (
                    <ListItemExplorerSearchForm
                        onSubmit={(values) => {
                            onSearch(values);
                            setSearchMode(false);
                        }}
                        initialValue={currentSearch}
                    />
                ) : (
                    <PageChanger
                        currentPage={currentPage}
                        totalPages={totalPages}
                        renderButton={(page, selected) => (
                            <ListItemsExplorerPageChangerButton
                                page={page}
                                selected={selected}
                                onClick={onClick}
                            >
                                {page}
                            </ListItemsExplorerPageChangerButton>
                        )}
                    />
                )}
            </div>
            {onSearch !== null ? (
                <Button
                    style="none"
                    className="p-2 hover:bg-gray-200 active:bg-gray-300"
                    onClick={() => setSearchMode(!searchMode)}
                >
                    <img src={searchIcon} alt="search icon" width="18px" />
                </Button>
            ) : (
                <></>
            )}
        </div>
    );
};

/**
 * Item of the list.
 *
 * @param onClick Callback to execute during click.
 * @param selected True if this item is selected.
 */
const ListItemsExplorerItem = ({ children, onClick, selected = false }) => {
    return (
        <Button
            className={
                'block w-full text-left p-4 border-b hover:bg-gray-50 active:bg-gray-200 ' +
                (selected ? 'bg-gray-200' : '')
            }
            style="none"
            onClick={onClick}
        >
            {children}
        </Button>
    );
};

/**
 * Component for making list from api collections.
 *
 * @param onLoading Callback to get items for a page
 * @param spinnerLegend Spinner legend during loading
 * @param emptyListMessage Message when no items has been found
 * @param itemListRender Function that return the component for render an item the list
 * @param itemFullRender Function that return the component for render the selected item
 * @param itemActions Object with functions that can be used to modify selected item
 * @param search Add a search field if true
 */
export const ListItemsExplorer = ({
    onLoading,
    spinnerLegend = (page) => `Chargement de la page ${page}...`,
    emptyListMessage = 'Aucun résultats',
    emptySelectionMessage = '',
    itemListRender = (item) => {},
    itemFullRender = (item, actions) => {},
    itemActions = {},
    search = false,
}) => {
    const [isLoading, setIsLoading] = useState(true);
    const [totalPages, setTotalPages] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);
    const [items, setItems] = useState([]);
    const [selectedItem, setSelectedItem] = useState(null);
    const [searchValue, setSearchValue] = useState('');

    // Use for update known items using the onLoading prop.
    const updateItems = () => {
        setIsLoading(true);
        return onLoading(currentPage, searchValue).then((response) => {
            setItems(response.data['hydra:member']);
            setTotalPages(Math.ceil(response.data['hydra:totalItems'] / 30));
            setIsLoading(false);
        });
    };

    useEffect(() => {
        updateItems();
    }, []);

    useEffect(() => {
        if (!isLoading) {
            updateItems().then(() => {
                if (totalPages > 1 && currentPage > totalPages) {
                    setCurrentPage(totalPages);
                }
            });
        }
    }, [currentPage]);

    useEffect(() => {
        if (!isLoading) {
            setSelectedItem(null);
            if (currentPage > 1) {
                setCurrentPage(1);
            } else {
                updateItems();
            }
        }
    }, [searchValue]);

    // Make each Action simpler to call
    let managedItemActions = {};
    Object.keys(itemActions).forEach((name) => {
        managedItemActions[name] = (payload = {}) => {
            const modifiedItem = itemActions[name](selectedItem, payload);
            console.log(modifiedItem);
            setSelectedItem(modifiedItem);

            if (modifiedItem === null) {
                setItems(items.filter((item) => item !== selectedItem));
            } else {
                setItems((current) =>
                    current.map((obj) => {
                        console.log(obj);
                        if (obj.orderReference == modifiedItem.orderReference) {
                            return { ...modifiedItem };
                        }
                        return obj;
                    }),
                );
            }
        };
    });

    return (
        <>
            <ListItemsExplorerPageChanger
                totalPages={totalPages}
                currentPage={currentPage}
                currentSearch={searchValue}
                onClick={(page) => {
                    setCurrentPage(page);
                }}
                onSearch={
                    search
                        ? (value) => {
                              setSearchValue(value.search);
                          }
                        : null
                }
            />
            <div className="border-x overflow-y-auto h-[400px]">
                {isLoading ? (
                    <div className="pt-10">
                        <Spinner legend={spinnerLegend(currentPage)} />
                    </div>
                ) : items.length === 0 ? (
                    <div className="text-center pt-10">
                        <p className="text-2xl">{emptyListMessage}</p>
                    </div>
                ) : (
                    items.map((item, index) => {
                        return (
                            <ListItemsExplorerItem
                                key={index}
                                onClick={() => {
                                    setSelectedItem(item);
                                }}
                                selected={item === selectedItem}
                            >
                                {itemListRender(item)}
                            </ListItemsExplorerItem>
                        );
                    })
                )}
            </div>
            <div className="border rounded-b">
                {selectedItem === null ? (
                    <div className="flex justify-center bg-gray-100 text-gray-400 py-10">
                        <p>{emptySelectionMessage}</p>
                    </div>
                ) : (
                    itemFullRender(selectedItem, managedItemActions)
                )}
            </div>
        </>
    );
};
