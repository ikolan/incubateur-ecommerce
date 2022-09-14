import React, { useEffect, useState } from 'react';

import { backendApi } from '../../api/backend/backendApi';
import { Button } from './Button';

const TagButton = ({ children, className, onClick }) => {
    return (
        <Button
            style="none"
            className={`rounded-xl m-1 p-1 ${className}`}
            onClick={onClick}
        >
            {children}
        </Button>
    );
};

/**
 * Formik Field component for select tags.
 *
 * @author Nicolas Benoit
 */
export const TagsInput = ({ form, field: { name, value } }) => {
    const [tags, setTags] = useState([]);

    const toggleSelected = (tagId) => {
        const toggle = (tag) => {
            tag.selected = !tag.selected;
            return tag;
        };
        setTags(
            tags.map((tag) => {
                return tag.id === tagId ? toggle(tag) : tag;
            }),
        );
    };

    useEffect(() => {
        backendApi.tag.getAll().then((response) => {
            setTags(
                response.data['hydra:member'].map((tag) => {
                    return {
                        id: tag.id,
                        label: tag.label,
                        selected: value.includes(tag.id),
                    };
                }),
            );
        });
    }, []);

    useEffect(() => {
        form.setFieldValue(
            name,
            tags.filter((tag) => tag.selected).map((tag) => tag.id),
        );
    }, [tags]);

    return (
        <div className="input">
            {tags
                .filter((tag) => tag.selected)
                .map((tag) => (
                    <TagButton
                        key={tag.id}
                        className="bg-secondary-light hover:bg-secondary active:bg-secondary-dark text-white font-bold"
                        onClick={() => toggleSelected(tag.id)}
                    >
                        {tag.label}
                    </TagButton>
                ))}
            <hr />
            {tags
                .filter((tag) => !tag.selected)
                .map((tag) => (
                    <TagButton
                        key={tag.id}
                        className="bg-gray-100 hover:bg-gray-200 active:bg-gray-300"
                        onClick={() => toggleSelected(tag.id)}
                    >
                        {tag.label}
                    </TagButton>
                ))}
        </div>
    );
};
