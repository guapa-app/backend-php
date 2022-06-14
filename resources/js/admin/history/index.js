import React from 'react'

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

import { withStyles } from '@material-ui/core/styles'
import Chip from '@material-ui/core/Chip'
import AssignmentTurnedInIcon from '@material-ui/icons/AssignmentTurnedIn'

import { parse } from 'query-string'
import { ucFirst } from '../utils/common'

export const HistoryIcon = AssignmentTurnedInIcon

const HistoryFilter = props => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <ReferenceInput label="User" source="user_id" reference="users">
            <AutocompleteInput
                optionValue="id"
                optionText={user => user && (user.name + ' - ' + user.phone)}
                shouldRenderSuggestions={val => val.trim().length >= 2 }
            />
        </ReferenceInput>
    </Filter>
)

export const HistoryList = props => (
    <List title="Medical history" {...props} filters={<HistoryFilter />}>
        <Datagrid>
            <TextField source="id" />
            <ReferenceField label="User" source="user_id" reference="users">
                <TextField source="name" />
            </ReferenceField>
            <FunctionField source="details" render={({ details }) => details && details.substring(0, 200)} />
            <DateField source="record_date" />
            <DateField source="created_at" />
            <EditButton />
        </Datagrid>
    </List>
)

const HistoryTitle = ({ record }) => {
    return <span>History: {record ? `"${record.title}"` : ''}</span>;
}

export const HistoryEdit = props => (
    <Edit title="Edit record" undoable={false} {...props}>
        <SimpleForm>
            <ReferenceInput label="User" source="user_id" reference="users">
                <AutocompleteInput
                    optionValue="id"
                    optionText={user => user && (user.name + ' - ' + user.phone)}
                    shouldRenderSuggestions={val => val.trim().length >= 2 }
                />
            </ReferenceInput>
            <TextInput source="details" multiline />
        </SimpleForm>
    </Edit>
)

export const HistoryCreate = props => {
    const params = parse(props.location.search)
    const user_id = params.user_id ? parseInt(params.user_id, 10) : ''
    const redirect = user_id ? `/users/${user_id}/show/1` : 'edit'
    return (
        <Create title="Create new history record" {...props}>
            <SimpleForm initialValues={{ user_id }} redirect={redirect}>
                <ReferenceInput label="User" source="user_id" reference="users">
                    <AutocompleteInput
                        optionValue="id"
                        optionText={user => user && (user.name + ' - ' + user.phone)}
                        shouldRenderSuggestions={val => val.trim().length >= 2 }
                    />
                </ReferenceInput>
                <TextInput source="details" multiline />
            </SimpleForm>
        </Create>
    )
}