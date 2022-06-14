import React from 'react'
import {
    List, Edit, Create, Delete, Datagrid, TextField,
    ChipField, EditButton, required, SimpleForm, SimpleShowLayout,
    TextInput, Filter, SelectInput, FunctionField, RichTextField,
    ImageInput, ReferenceInput, AutocompleteInput, Show,
    FormDataConsumer, ReferenceField, DateField, ShowButton
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'
import { withStyles } from '@material-ui/core/styles'
import Icon from '@material-ui/core/Icon'
import LocationOnIcon from '@material-ui/icons/LocationOn'

import { OrderStatuses } from '../utils/constants'
import { parse } from 'query-string'

export const OrderIcon = LocationOnIcon

const OrderFilter = props => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <SelectInput source="status" choices={OrderStatuses} />
    </Filter>
)

export const OrderList = props => (
    <List title="Orders" {...props} filters={<OrderFilter />}>
        <Datagrid>
            <TextField source="id" />
            <ReferenceField source="user_id" reference="users" label="User">
                <TextField source="name" />
            </ReferenceField>
            <ReferenceField source="vendor_id" reference="vendors" label="Vendor">
                <TextField source="name" />
            </ReferenceField>
            <TextField source="status" />
            <TextField source="name" />
            <TextField source="phone" />
            <ShowButton />
            <EditButton />
        </Datagrid>
    </List>
)

const OrderTitle = ({ record }) => {
    return <span>Order {record ? `"${record.id}"` : ''}</span>;
}

const styles = {
    card: {
        overflow: 'visible',
    },
}

export const OrderEdit = props => (
    <Edit title="Edit order" undoable={false} {...props}>
        <SimpleForm>
            <SelectInput source="status" choices={OrderStatuses} />
            <TextInput source="phone" />
            <TextInput source="name" />
        </SimpleForm>
    </Edit>
)

export const OrderShow = props => (
    <Show {...props}>
        <SimpleShowLayout>
            <TextField source="id" />
            <ReferenceField source="user_id" reference="users" label="User">
                <TextField source="name" />
            </ReferenceField>
            <ReferenceField source="vendor_id" reference="vendors" label="Vendor">
                <TextField source="name" />
            </ReferenceField>
            <TextField source="status" />
            <TextField source="name" />
            <TextField source="phone" />
            <RichTextField source="note" />
        </SimpleShowLayout>
    </Show>
)
