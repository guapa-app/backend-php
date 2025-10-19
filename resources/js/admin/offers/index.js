import React, { Fragment, useEffect, useState } from 'react'

import { useField } from 'react-final-form'

import {
    List, Edit, Datagrid, TextField, Create,
    ChipField, EditButton, required, SimpleForm,
    TextInput, Filter, ReferenceInput, AutocompleteInput, ReferenceField,
    SelectInput, DateField, NumberInput, SimpleShowLayout,
    Show, FunctionField, RichTextField, ShowButton
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'

import { withStyles } from '@material-ui/core/styles'
import Chip from '@material-ui/core/Chip'
import AssignmentTurnedInIcon from '@material-ui/icons/AssignmentTurnedIn'

import _ from 'lodash'
import { joinStrings, ucFirst } from '../utils/common'

export const OfferIcon = AssignmentTurnedInIcon

const offerStatuses = [
    {
        id: 'active',
        name: 'Active',
    },
    {
        id: 'incoming',
        name: 'Incoming',
    },
    {
        id: 'expired',
        name: 'Expired',
    }
]

const OfferFilter = props => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <SelectInput label="Offer status" source="status" choices={offerStatuses} />
    </Filter>
)

export const OfferList = props => (
    <List title="Offers" {...props} filters={<OfferFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="title" />
            <ReferenceField label="Product" source="product_id" reference="products">
                <TextField source="title" />
            </ReferenceField>
            <TextField label="Discount" source="discount_string" />
            <ChipField source="status" />
            <DateField label="Last update" source="updated_at" />
            <ShowButton />
            <EditButton />
        </Datagrid>
    </List>
)

const OfferTitle = ({ record }) => {
    return <span>Offer: {record ? `"${record.title}"` : ''}</span>;
}

export const OfferEdit = props => (
    <Edit title="Edit offer" undoable={false} {...props}>
        <SimpleForm>
            <ReferenceInput label="Product" source="product_id" reference="products" validate={required()}>
                <AutocompleteInput
                    optionValue="id"
                    optionText={product => product.id + ' : ' + product.title}
                    shouldRenderSuggestions={val => val.trim().length >= 2 }
                />
            </ReferenceInput>
            <TextInput source="title" validate={required()} />
            <TextInput source="description" multiline />
            <NumberInput label="Discount percent" source="discount" validate={required()} />
        </SimpleForm>
    </Edit>
)

export const OfferCreate = props => (
    <Create title="Create new Offer" {...props}>
        <SimpleForm>
            <ReferenceInput label="Product" source="product_id" reference="products" validate={required()}>
                <AutocompleteInput
                    optionValue="id"
                    optionText={product => product.id + ' : ' + product.title}
                    shouldRenderSuggestions={val => val.trim().length >= 2 }
                />
            </ReferenceInput>
            <TextInput source="title" validate={required()} />
            <TextInput source="description" multiline />
            <NumberInput label="Discount percent" source="discount" validate={required()} />
        </SimpleForm>
    </Create>
)

export const OfferShow = props => (
    <Show {...props}>
        <SimpleShowLayout>
            <TextField source="id" />
            <ReferenceField label="Product" source="product_id" reference="products">
                <TextField source="title" />
            </ReferenceField>
            <TextField source="title" />
            <RichTextField source="description" />
            <TextField label="Discount" source="discount_string" />
            <ChipField source="status" />
            <DateField label="Last update" source="updated_at" />
            <DateField source="starts_at" />
            <DateField source="expires_at" />
        </SimpleShowLayout>
    </Show>
)
