import { Field, Form, Formik } from 'formik';
import React, { useEffect, useState } from 'react';

import editIcon from '../../assets/icons/edit.svg';
import trashIcon from '../../assets/icons/trash.svg';
import xmarkIcon from '../../assets/icons/xmark.svg';
import { Button } from '../utils/Button';
import Input from '../utils/Input';
import { Spinner } from '../utils/Spinner';

const SmallListItemExplorerSearchForm = ({ onSearch }) => {
    return (
        <Formik initialValues={{ search: '' }} onSubmit={() => {}}>
            <Form>
                <Field
                    name="search"
                    component={Input}
                    placeholder="Recherche..."
                    onKeyUp={(input) => {
                        onSearch(input.target.value);
                    }}
                />
            </Form>
        </Formik>
    );
};

const SmallListItemExplorerItem = ({ children, onDelete, onEdit }) => {
    const [isEditing, setIsEditing] = useState(false);

    return (
        <div className="flex justify-between items-center border-b last:border-b-0">
            {isEditing ? (
                <>
                    <Formik
                        initialValues={{ input: children }}
                        onSubmit={(values) => {
                            onEdit(values.input);
                        }}
                    >
                        <Form className="w-full">
                            <Field
                                className="m-2 h-[30px]"
                                name="input"
                                component={Input}
                            />
                        </Form>
                    </Formik>
                    <div className="ml-2">
                        <Button
                            style="none"
                            className="p-2 hover:bg-gray-100"
                            onClick={() => setIsEditing(false)}
                        >
                            <img src={xmarkIcon} alt="xmarkIcon" width="15px" />
                        </Button>
                    </div>
                </>
            ) : (
                <>
                    <p className="p-2">{children}</p>
                    <div>
                        <Button
                            style="none"
                            className="p-2 hover:bg-gray-100"
                            onClick={() => setIsEditing(true)}
                        >
                            <img src={editIcon} alt="editIcon" width="15px" />
                        </Button>
                        <Button
                            style="none"
                            className="p-2 hover:bg-gray-100"
                            doubleCheck="Sûre ?"
                            onClick={onDelete}
                        >
                            <img src={trashIcon} alt="trashIcon" width="15px" />
                        </Button>
                    </div>
                </>
            )}
        </div>
    );
};

const SmallListItemExplorerAddForm = ({ onSubmit }) => {
    return (
        <Formik
            initialValues={{ input: '' }}
            onSubmit={(values, { resetForm }) => {
                onSubmit(values);
                resetForm();
            }}
        >
            <Form className="flex">
                <div className="flex-1">
                    <Field
                        className="h-[30px] border-r-0 rounded-r-none"
                        name="input"
                        component={Input}
                    />
                </div>
                <Button className="flex-shrink-0 h-[30px] rounded-l-none" type="submit">
                    Ajouter
                </Button>
            </Form>
        </Formik>
    );
};

/**
 * Small list explorer for basic editing.
 * Used mainly for tags & categories.
 *
 * @param onLoading Function to obtain labels.
 * @param onAdding Function for adding a label.
 * @param onDelete Function for delete a label.
 * @param onEdit Function for edit a label.
 * @param spinnerLegend Legend of the spinner during loading.
 */
export const SmallListItemExplorer = ({
    onLoading,
    onAdding = (item) => {},
    onDelete = (item) => {},
    onEdit = (item) => {},
    spinnerLegend = 'Chargement en cours...',
}) => {
    const [items, setItems] = useState([]);
    const [search, setSearch] = useState('');
    const [isLoading, setIsLoading] = useState(true);

    // Use for update known items using the onLoading prop.
    const updateItems = () => {
        setIsLoading(true);
        return onLoading().then((response) => {
            setItems(
                response.data['hydra:member'].map((member) => {
                    return {
                        id: member.id,
                        label: member.label,
                    };
                }),
            );
            setIsLoading(false);
        });
    };

    // Use for adding a label in the items.
    const addingItem = (value) => {
        if (!items.map((i) => i.label).includes(value)) {
            onAdding(value).then((response) => {
                setItems([...items, { id: response.data.id, label: value }]);
            });
        }
    };

    // Use for edit a label.
    const editItem = (id, label) => {
        let newItem = { id: id, label: label };
        onEdit(newItem);
        setItems(
            items.map((item) => {
                return id === item.id ? newItem : item;
            }),
        );
    };

    // Use for delete a label.
    const deleteItem = (item) => {
        onDelete(item);
        setItems(items.filter((i) => i !== item));
    };

    useEffect(() => {
        updateItems();
    }, []);

    return (
        <div className="border rounded">
            {isLoading ? (
                <Spinner className="my-10" legend={spinnerLegend} />
            ) : (
                <>
                    <div className="m-2">
                        <SmallListItemExplorerSearchForm
                            onSearch={(searchInput) => setSearch(searchInput)}
                        />
                    </div>
                    <hr />
                    <div className="h-[300px] overflow-y-auto">
                        {items.map((item) => {
                            if (search === '' || item.label.includes(search)) {
                                return (
                                    <SmallListItemExplorerItem
                                        key={item.label}
                                        onDelete={() => deleteItem(item)}
                                        onEdit={(label) => editItem(item.id, label)}
                                    >
                                        {item.label}
                                    </SmallListItemExplorerItem>
                                );
                            }
                        })}
                    </div>
                    <hr />
                    <div className="m-2">
                        <SmallListItemExplorerAddForm
                            onSubmit={(values) => addingItem(values.input)}
                        />
                    </div>
                </>
            )}
        </div>
    );
};
