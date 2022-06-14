import React from 'react'

import {
    List, Show, Delete, Datagrid, TextField, ReferenceInput,
    ChipField, ShowButton, SimpleForm, ReferenceField,
    TextInput, Filter, FunctionField, AutocompleteInput,
    SelectInput, SimpleShowLayout, RichTextField,
    ListButton, DeleteButton, BooleanField
} from 'react-admin'

import MarkReadButton from './MarkReadButton'

import { withStyles } from '@material-ui/core/styles'
import CardActions from '@material-ui/core/CardActions'
import MessageIcon1 from '@material-ui/icons/Message'

export const MessageIcon = MessageIcon1

const MessageFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <ReferenceInput label="Filter by user" source="user_id" reference="users">
            <AutocompleteInput
                optionValue="id"
                optionText={record => record.name ? record.name + ' - ' + record.phone : record.phone + ' - ' + (record.email || '')}
                inputValueMatcher={(input, suggestion, getOptionText) => input.toLowerCase().trim() === suggestion.name || input.toLowerCase().trim() === getOptionText(suggestion).toLowerCase().trim()}
                shouldRenderSuggestions={val => val.trim().length >= 2 }
            />
        </ReferenceInput>
        <SelectInput label="Read/Unread" source="read" choices={[
            {id: '', name: 'All'},
            { id: '1', name: 'Read' },
            { id: '0', name: 'Unread' },
        ]} />
    </Filter>
)

export const MessageList = (props) => (
    <List title="Messages" {...props} filters={<MessageFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="subject" />
            <FunctionField label="Message" render={record => record.body && record.body.substring(0, 30) + '...'} />
            <ReferenceField label="User" source="user_id" reference="users" allowEmpty>
                <TextField source="phone" />
            </ReferenceField>
            <BooleanField source="is_read" label="Read" />
            <TextField source="created_at" />
            <ShowButton />
            <MarkReadButton />
        </Datagrid>
    </List>
)

const MessageTitle = ({ record }) => {
    return <span>Message {record ? `"${record.subject}"` : '#' + record.id}</span>;
}

const cardActionStyle = {
    justifyContent: 'flex-end',
};

const MessageShowActions = ({ basePath, data, resource }) => (
    <CardActions style={cardActionStyle}>
        <MarkReadButton record={data} />
        <ListButton basePath={basePath} />
        <DeleteButton basePath={basePath} record={data} resource={resource} />
    </CardActions>
)


export const MessageShow = ({ hasShow, ...props }) => (
    <Show {...props} actions={<MessageShowActions />}>
        <SimpleShowLayout>
            <TextField source="id" />
            <ReferenceField label="User" source="user_id" reference="users">
                <TextField source="phone" />
            </ReferenceField>
            <TextField source="subject" />
            <RichTextField source="body" />
            <BooleanField source="is_read" />
            <TextField source="read_at" />
        </SimpleShowLayout>
    </Show>
)
