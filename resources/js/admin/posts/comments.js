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

export const CommentIcon = AssignmentTurnedInIcon

const CommentFilter = props => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <ReferenceInput label="Post" source="post_id" reference="posts">
            <AutocompleteInput
                optionValue="id"
                optionText="title"
                shouldRenderSuggestions={val => val.trim().length >= 2 }
            />
        </ReferenceInput>
    </Filter>
)

export const CommentList = props => (
    <List title="Comments" {...props} filters={<CommentFilter />}>
        <Datagrid>
            <TextField source="id" />
            <FunctionField label="Author" source="user_id" render={({ user, user_type }) => user && (ucFirst(user_type) + ' - ' + user.name)} />
            <ReferenceField label="Post" source="post_id" reference="posts">
                <TextField source="title" />
            </ReferenceField>
            <FunctionField source="content" render={({ content }) => content && content.substring(0, 50)} />
            <DateField source="created_at" />
            <EditButton />
        </Datagrid>
    </List>
)

const CommentTitle = ({ record }) => {
    return <span>Comment: {record ? `"${record.title}"` : ''}</span>;
}

export const CommentEdit = props => (
    <Edit title="Edit Comment" undoable={false} {...props}>
        <SimpleForm>
            <ReferenceInput label="Post" source="post_id" reference="posts">
                <AutocompleteInput
                    optionValue="id"
                    optionText="title"
                    shouldRenderSuggestions={val => val.trim().length >= 2 }
                />
            </ReferenceInput>
            <TextInput source="content" multiline />
        </SimpleForm>
    </Edit>
)

export const CommentCreate = props => {
    const params = parse(props.location.search)
    const post_id = params.post_id ? parseInt(params.post_id, 10) : ''
    const redirect = post_id ? `/posts/${post_id}/show/1` : 'edit'
    return (
        <Create title="Create new Comment" {...props}>
            <SimpleForm initialValues={{ post_id }} redirect={redirect}>
                <ReferenceInput label="Post" source="post_id" reference="posts">
                    <AutocompleteInput
                        optionValue="id"
                        optionText="title"
                        shouldRenderSuggestions={val => val.trim().length >= 2 }
                    />
                </ReferenceInput>
                <TextInput source="content" multiline />
            </SimpleForm>
        </Create>
    )
}