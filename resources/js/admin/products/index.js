import React, { Fragment, useEffect, useState } from 'react'

import { useField } from 'react-final-form'

import {
    List, Edit, Datagrid, TextField, Create, ReferenceArrayInput,
    ChipField, EditButton, required, SimpleForm, FormDataConsumer,
    TextInput, Filter, ReferenceInput, AutocompleteInput, ReferenceField,
    SelectInput, DateField, NumberInput, ImageInput, ImageField,
    TabbedShowLayout, Tab, Show, FunctionField, ReferenceManyField,
    RichTextField, ShowButton, TabbedForm, FormTab, BooleanInput,
    withDataProvider, SelectArrayInput
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'

import CategoryInput from '../customInputs/CategoryInput'
import AttributesInput from '../customInputs/AttributesInput'
import LocationInput from '../customInputs/LocationInput'

import { withStyles } from '@material-ui/core/styles'
import Chip from '@material-ui/core/Chip'
import AssignmentTurnedInIcon from '@material-ui/icons/AssignmentTurnedIn'

import _ from 'lodash'
import { joinStrings, ucFirst } from '../utils/common'
import {value} from "lodash/seq";

export const ProductIcon = AssignmentTurnedInIcon

const productStatuses = [
    { id: 'Published', name: 'Published' },
    { id: 'Draft', name: 'Draft' },
]

const productReviewOptions = [
    { id: 'Approved', name: 'Approved' },
    { id: 'Pending', name: 'Pending' },
    { id: 'Blocked', name: 'Blocked' },
]

const types = [
    // { id: 'product', name: 'Product' },
    { id: 'service', name: 'Service' },
]

const ProductFilter = props => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <SelectInput label="Product status" source="status" choices={productStatuses} />
        <SelectInput label="Product review" source="review" choices={productReviewOptions} />
    </Filter>
)

export const ProductList = props => (
    <List title="Products" {...props} filters={<ProductFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="title" />
            <ReferenceField label="Vendor" source="vendor_id" reference="vendors">
                <TextField source="name" />
            </ReferenceField>
            <TextField source="price" />
            <FunctionField source="type" render={record => <Chip label={ucFirst(record.type)} />} />
            <ChipField source="status" />
            <DateField source="created_at" />
            <ShowButton />
            <EditButton />
        </Datagrid>
    </List>
)

const ProductTitle = ({ record }) => {
    return <span>Product: {record ? `"${record.title}"` : ''}</span>;
}

export const ProductEdit = props => (
    <Edit title="Edit product" undoable={false} {...props}>
        <TabbedForm>
            <FormTab label="Details">
                <ReferenceInput label="Vendor" source="vendor_id" reference="vendors" validate={required()}>
                    <AutocompleteInput
                        optionValue="id"
                        optionText="name"
                        shouldRenderSuggestions={val => val.trim().length >= 2 }
                    />
                </ReferenceInput>
                <TextInput source="title" validate={required()} />
                <TextInput source="url" initialValue=" " />
                <TextInput source="description" initialValue=" " multiline />
                <NumberInput source="price" validate={required()} />
                <SelectInput source="type" choices={types} validate={required()} />
                <ReferenceArrayInput label="Categories" source="category_ids" reference="taxonomies" filter={{parents: 1, type: 'specialty'}}>
                    <SelectArrayInput optionText="title.en" />
                </ReferenceArrayInput>
                <FormDataConsumer>
                    {({ formData, dispatch, ...rest }) => formData.vendor_id &&
                        <ReferenceArrayInput
                            {...rest}
                            key={'vendor_' + formData.vendor_id}
                            label="Addresses"
                            source="address_ids"
                            reference="addresses"
                            filter={{addressable_type: 'vendor', addressable_id: formData.vendor_id}}
                        >
                            <SelectArrayInput optionText={address => `#${address.id} - ${address.city.name.en} - ${address.address_1}`} />
                        </ReferenceArrayInput>
                    }
                </FormDataConsumer>
                <SelectInput source="status" choices={productStatuses} validate={required()} />
                <SelectInput source="review" choices={productReviewOptions} validate={required()} />
                <TextInput source="terms" multiline />
            </FormTab>
            <FormTab label="Images">
                <ImageInput multiple source="media" label="Product images" accept="image/*" placeholder={<p>Drop your files here</p>} validate={required()}>
                    <ImageField source="small" title="file_name" />
                </ImageInput>
            </FormTab>
        </TabbedForm>
    </Edit>
)

export const ProductCreate = props => (
    <Create title="Create new product" {...props}>
        <TabbedForm>
            <FormTab label="Details">
                <ReferenceInput label="Vendor" source="vendor_id" reference="vendors" validate={required()}>
                    <AutocompleteInput
                        optionValue="id"
                        optionText="name"
                        shouldRenderSuggestions={val => val.trim().length >= 2 }
                    />
                </ReferenceInput>
                <TextInput source="title" validate={required()} />
                <TextInput source="url" />
                <TextInput source="description" multiline />
                <NumberInput source="price" validate={required()} />
                <SelectInput source="type" choices={types} validate={required()} />
                <ReferenceArrayInput label="Categories" source="category_ids" reference="taxonomies" filter={{parents: 1, type: 'specialty'}}>
                    <SelectArrayInput optionText="title.en" />
                </ReferenceArrayInput>
                <FormDataConsumer>
                    {({ formData, dispatch, ...rest }) => formData.vendor_id &&
                        <ReferenceArrayInput
                            {...rest}
                            key={'vendor_' + formData.vendor_id}
                            label="Addresses"
                            source="address_ids"
                            reference="addresses"
                            filter={{addressable_type: 'vendor', addressable_id: formData.vendor_id}}
                        >
                            <SelectArrayInput optionText={address => `#${address.id} - ${address.city.name.en} - ${address.address_1}`} />
                        </ReferenceArrayInput>
                    }
                </FormDataConsumer>
                <SelectInput source="status" choices={productStatuses} validate={required()} />
                <SelectInput source="review" choices={productReviewOptions} validate={required()} />
                <TextInput source="terms" multiline />
            </FormTab>
            <FormTab label="Images">
                <ImageInput multiple source="media" label="Product images" accept="image/*" placeholder={<p>Drop your files here</p>} validate={required()}>
                    <ImageField source="src" title="file_name" />
                </ImageInput>
            </FormTab>
        </TabbedForm>
    </Create>
)

export const ProductShow = props => (
    <Show {...props}>
        <TabbedShowLayout>
            <Tab label="Product details">
                <ReferenceField label="Vendor" source="vendor_id" reference="vendors">
                    <TextField source="name" />
                </ReferenceField>
                <TextField label="Product Name" source="title" />
                <TextField label="URL" source="url" />
                <FunctionField label="Price" render={record => record.price + ' SAR'} />
                <FunctionField source="type" render={record => <Chip label={ucFirst(record.type)} />} />
                <ChipField source="status" />
                <ChipField source="review" />
                {/*<ReferenceField label="Category" source="category_id" reference="categories">
                    <TextField source="name" />
                </ReferenceField>*/}
                <RichTextField source="description" />
                <RichTextField source="terms" />
                <DateField label="Date created" source="created_at" />
                <DateField label="Last update" source="updated_at" />
                <ImageField source="media" src="small" title="name" label="Product images" />
            </Tab>
        </TabbedShowLayout>
    </Show>
)
