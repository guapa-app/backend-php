import React from 'react'
import {
    List, Edit, Create, Delete, Datagrid, TextField, ReferenceField,
    ChipField, EditButton, required, SimpleForm, DateField,
    TextInput, Filter, SelectInput, FunctionField, NumberInput,
    ImageInput, ImageField, ReferenceInput, AutocompleteInput,
    FormDataConsumer
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'

import { ucFirst } from '../utils/common'

import StarIcon from '@material-ui/icons/Star'

export const ReviewIcon = StarIcon

const ReviewableTypes = [
    {id: 'vendor', name: 'Vendor'},
    {id: 'product', 'name': 'Product'},
]

const ReviewFilter = props => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <NumberInput source="stars" />
        <SelectInput source="reviewable_type" choices={ReviewableTypes} />
        {props.filterValues.reviewable_type &&
            <ReferenceInput
                key={props.filterValues.reviewable_type}
                source="reviewable_id"
                label="Reviewable entity"
                reference={props.filterValues.reviewable_type + 's'}
            >
                <AutocompleteInput
                    optionValue="id"
                    optionText={({ name, title }) => title || name}
                    shouldRenderSuggestions={val => val && val.trim().length >= 2 }
                />
            </ReferenceInput>
        }
    </Filter>
)

export const ReviewList = (props) => (
    <List title="Reviews" {...props} filters={<ReviewFilter />}>
        <Datagrid>
            <TextField source="id" />
            <ReferenceField source="user_id" reference="users">
                <FunctionField render={user => user.name + ' - ' + user.phone} />
            </ReferenceField>
            <FunctionField source="reviewable_type" render={({ reviewable, reviewable_type }) => {
                return ucFirst(reviewable_type) + ': ' + (reviewable.title || reviewable.name)
            }} />
            <TextField source="stars" />
            <DateField source="created_at" />
            <EditButton />
        </Datagrid>
    </List>
)

const ReviewTitle = ({ record }) => {
    return <span>Review {record ? `"${record.name}"` : ''}</span>;
}

export const ReviewEdit = props => (
    <Edit title="Edit review" undoable={false} {...props}>
        <SimpleForm>
            <TextInput disabled source="id" />
            <ReferenceInput label="User" source="user_id" reference="users" validate={required()}>
                <AutocompleteInput
                    optionValue="id"
                    optionText={user => user.name + ' - ' + user.phone}
                    shouldRenderSuggestions={val => val.trim().length >= 2 }
                />
            </ReferenceInput>
            <SelectInput source="reviewable_type" choices={ReviewableTypes} />
            <FormDataConsumer>
                {({ formData, ...rest }) => formData.reviewable_type && (
                    <ReferenceInput
                        key={formData.reviewable_type}
                        source="reviewable_id"
                        label="Reviewable entity"
                        reference={formData.reviewable_type + 's'}
                        validate={required()}
                    >
                        <AutocompleteInput
                            optionValue="id"
                            optionText={reviewable => reviewable.title || reviewable.name}
                            shouldRenderSuggestions={val => val && val.trim().length >= 2 }
                        />
                    </ReferenceInput>
                )}
            </FormDataConsumer>
            <NumberInput source="stars" />
            <TextInput source="comment" multiline />
        </SimpleForm>
    </Edit>
)

export const ReviewCreate = props => (
    <Create {...props}>
        <SimpleForm>
            <ReferenceInput label="User" source="user_id" reference="users" validate={required()}>
                <AutocompleteInput
                    optionValue="id"
                    optionText={user => user.name + ' - ' + user.phone}
                    shouldRenderSuggestions={val => val.trim().length >= 2 }
                />
            </ReferenceInput>
            <SelectInput source="reviewable_type" choices={ReviewableTypes} />
            <FormDataConsumer>
                {({ formData, ...rest }) => formData.reviewable_type && (
                    <ReferenceInput
                        key={formData.reviewable_type}
                        source="reviewable_id"
                        label="Reviewable entity"
                        reference={formData.reviewable_type + 's'}
                        validate={required()}
                    >
                        <AutocompleteInput
                            optionValue="id"
                            optionText={reviewable => reviewable.title || reviewable.name}
                            shouldRenderSuggestions={val => val && val.trim().length >= 2 }
                        />
                    </ReferenceInput>
                )}
            </FormDataConsumer>
            <NumberInput source="stars" />
            <TextInput source="comment" multiline />
        </SimpleForm>
    </Create>
)
