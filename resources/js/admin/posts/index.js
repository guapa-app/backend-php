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

import AddCommentButton from '../components/AddCommentButton'

import { withStyles } from '@material-ui/core/styles'
import Chip from '@material-ui/core/Chip'
import AssignmentTurnedInIcon from '@material-ui/icons/AssignmentTurnedIn'

import _ from 'lodash'
import { joinStrings, ucFirst } from '../utils/common'

export const PostIcon = AssignmentTurnedInIcon

const postStatuses = [
    { id: 1, name: 'Published' },
    { id: 2, name: 'Draft' },
]

const PostFilter = props => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <ReferenceInput label="Category" source="category_id" reference="taxonomies">
            <SelectInput optionText="title.en" />
        </ReferenceInput>
        <ReferenceInput label="Author" source="admin_id" reference="admins">
            <AutocompleteInput
                optionValue="id"
                optionText="name"
                shouldRenderSuggestions={val => val.trim().length >= 2 }
            />
        </ReferenceInput>
        <SelectInput source="status" choices={postStatuses} />
    </Filter>
)

export const PostList = props => (
    <List title="Posts" {...props} filters={<PostFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="title" />
            <ReferenceField label="Author" source="admin_id" reference="admins">
                <TextField source="name" />
            </ReferenceField>
            <ReferenceField label="Category" source="category_id" reference="taxonomies">
                <TextField source="title.en" />
            </ReferenceField>
            <FunctionField source="status" render={({ status }) => {
                const s = postStatuses.find(st => st.id === status)
                return s && s.name
            }} />
            <DateField source="created_at" />
            <ShowButton />
            <EditButton />
        </Datagrid>
    </List>
)

const PostTitle = ({ record }) => {
    return <span>Post: {record ? `"${record.title}"` : ''}</span>;
}

export const PostEdit = props => (
    <Edit title="Edit Post" undoable={false} {...props}>
        <SimpleForm>
            <ReferenceInput label="Category" source="category_id" reference="taxonomies">
                <SelectInput optionText="title.en" />
            </ReferenceInput>
            <TextInput source="title" validate={required()} />
            <TextInput source="content" multiline />
            <SelectInput source="status" choices={postStatuses} />
            <TextInput source="youtube_url" />
            <ImageInput multiple source="media" label="Post images" accept="image/*" placeholder={<p>Drop your files here</p>} validate={required()}>
                <ImageField source="small" title="file_name" />
            </ImageInput>
        </SimpleForm>
    </Edit>
)

export const PostCreate = props => (
    <Create title="Create new Post" {...props}>
        <SimpleForm>
            <ReferenceInput label="Category" source="category_id" reference="taxonomies">
                <SelectInput optionText="title.en" />
            </ReferenceInput>
            <TextInput source="title" validate={required()} />
            <TextInput source="content" multiline />
            <SelectInput source="status" choices={postStatuses} />
            <TextInput source="youtube_url" />
            <ImageInput multiple source="media" label="Post images" accept="image/*" placeholder={<p>Drop your files here</p>} validate={required()}>
                <ImageField source="src" title="file_name" />
            </ImageInput>
        </SimpleForm>
    </Create>
)

export const PostShow = props => (
    <Show {...props}>
        <TabbedShowLayout>
            <Tab label="Post details">
                <TextField source="id" />
                <TextField source="title" />
                <ReferenceField label="Author" source="admin_id" reference="admins">
                    <TextField source="name" />
                </ReferenceField>
                <ReferenceField label="Category" source="category_id" reference="taxonomies">
                    <TextField source="title.en" />
                </ReferenceField>
                <FunctionField source="status" render={({ status }) => {
                    const s = postStatuses.find(st => st.id === status)
                    return s && s.name
                }} />
                <DateField source="created_at" />
                <DateField source="updated_at" />
                <TextField source="youtube_url" />
                <ImageField source="media" src="small" />
            </Tab>
            <Tab label="Comments">
                <ReferenceManyField source="id" target="post_id" reference="comments" label="">
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
                </ReferenceManyField>
                <AddCommentButton />
            </Tab>
        </TabbedShowLayout>
    </Show>
)
