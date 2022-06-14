import React from 'react'
import {
    List, Edit, Create, Delete, Datagrid, TextField,
    ChipField, EditButton, required, SimpleForm,
    TextInput, Filter, SelectInput, FunctionField,
    ImageInput, ReferenceInput, AutocompleteInput,
    FormDataConsumer, ReferenceField, DateField
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'
import LocationInput from '../customInputs/LocationInput'
import { withStyles } from '@material-ui/core/styles'
import Icon from '@material-ui/core/Icon'
import LocationOnIcon from '@material-ui/icons/LocationOn'

import { AddressTypes } from '../utils/constants'
import { parse } from 'query-string'

export const AddressIcon = LocationOnIcon

const AddressableTypes = [
    {id: 'vendor', name: 'Vendor'},
    {id: 'user', 'name': 'User'},
]

const AddressFilter = props => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <SelectInput source="type" choices={AddressTypes} />
        <ReferenceInput
            source="city_id"
            reference="cities"
        >
            <SelectInput optionText="name.en" />
        </ReferenceInput>
        <SelectInput source="addressable_type" choices={AddressableTypes} />
        {props.filterValues.addressable_type &&
            <ReferenceInput
                key={props.filterValues.addressable_type}
                source="addressable_id"
                label="Addressable entity"
                reference={props.filterValues.addressable_type + 's'}
            >
                <AutocompleteInput
                    optionValue="id"
                    optionText={addressable => addressable.name + ' - ' + addressable.phone}
                    shouldRenderSuggestions={val => val && val.trim().length >= 2 }
                />
            </ReferenceInput>
        }
    </Filter>
)

export const AddressList = props => (
    <List title="Addresses" {...props} filters={<AddressFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="addressable.name" label="Owner" />
            <TextField source="title" />
            <ReferenceField source="city_id" reference="cities" allowEmpty>
                <TextField source="name.en" />
            </ReferenceField>
            <FunctionField source="type" render={({ type }) => {
                const t = AddressTypes.find(addressType => addressType.id === type)
                return t && t.name
            }} />
            <TextField source="address_1" />
            <TextField source="postal_code" />
            <TextField source="phone" />
            <DateField source="created_at" />
            <EditButton />
        </Datagrid>
    </List>
)

const AddressTitle = ({ record }) => {
    return <span>Address {record ? `"${record.name}"` : ''}</span>;
}

const styles = {
    card: {
        overflow: 'visible',
    },
}

const MySelectInput = ({ type, choices, ...props }) => {
    return <SelectInput {...props} optionText="title.en" choices={choices ? choices.filter(tax => tax.type === type) : []} />
}

const AddressEditComponent = props => (
    <Edit title="Edit address" undoable={false} {...props}>
        <SimpleForm>
            <SelectInput source="addressable_type" choices={AddressableTypes} />
            <FormDataConsumer>
                {({ formData, ...rest }) => formData.addressable_type && (
                    <ReferenceInput
                        key={formData.addressable_type}
                        source="addressable_id"
                        label="Addressable entity"
                        reference={formData.addressable_type + 's'}
                        validate={required()}
                    >
                        <AutocompleteInput
                            optionValue="id"
                            optionText={addressable => addressable.name + ' - ' + addressable.phone}
                            shouldRenderSuggestions={val => val && val.trim().length >= 2 }
                        />
                    </ReferenceInput>
                )}
            </FormDataConsumer>
            <TextInput source="title" />
            <SelectInput source="type" choices={AddressTypes} />
            <ReferenceInput label="City" source="city_id" reference="cities" validate={required()}>
                <SelectInput optionText="name.en" optionValue="id" />
            </ReferenceInput>
            <TextInput source="address_1" label="Address 1" validate={required()} />
            <TextInput source="address_2" label="Address 2" />
            <TextInput source="postal_code" label="Postal code" />
            <TextInput source="phone" />
            <LocationInput />
        </SimpleForm>
    </Edit>
)

const AddressCreateComponent = props => {
    const params = parse(props.location.search)
    const addressable_type = params.addressable_type || ''
    const addressable_id = params.addressable_id ? parseInt(params.addressable_id, 10) : ''
    const redirect = (addressable_type && addressable_id) ? `/${addressable_type}s/${addressable_id}/show/1` : 'edit'
    return (
        <Create {...props}>
            <SimpleForm initialValues={{ addressable_type, addressable_id}} redirect={redirect}>
                <SelectInput source="addressable_type" choices={AddressableTypes} />
                <FormDataConsumer>
                    {({ formData, ...rest }) => formData.addressable_type && (
                        <ReferenceInput
                            key={formData.addressable_type}
                            source="addressable_id"
                            label="Addressable entity"
                            reference={formData.addressable_type + 's'}
                            validate={required()}
                        >
                            <AutocompleteInput
                                optionValue="id"
                                optionText={addressable => addressable.name + ' - ' + addressable.phone}
                                shouldRenderSuggestions={val => val && val.trim().length >= 2 }
                            />
                        </ReferenceInput>
                    )}
                </FormDataConsumer>
                <TextInput source="title" />
                <SelectInput source="type" choices={AddressTypes} />
                <ReferenceInput label="City" source="city_id" reference="cities" validate={required()}>
                    <SelectInput optionText="name.en" optionValue="id" />
                </ReferenceInput>
                <TextInput source="address_1" label="Address 1" validate={required()} />
                <TextInput source="address_2" label="Address 2" />
                <TextInput source="postal_code" label="Postal code" />
                <TextInput source="phone" />
                <LocationInput />
            </SimpleForm>
        </Create>
    )
}

export const AddressCreate = withStyles(styles)(AddressCreateComponent)

export const AddressEdit = withStyles(styles)(AddressEditComponent)
