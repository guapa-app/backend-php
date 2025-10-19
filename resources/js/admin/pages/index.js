import React from 'react'

import {
    List, Edit, Create, Show, Datagrid, SimpleForm,
    TextField, BooleanField, DateField, EditButton,
    TextInput, BooleanInput, Filter, required
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'

import StayCurrentPortraitIcon from '@material-ui/icons/StayCurrentPortrait'

export const PageIcon = StayCurrentPortraitIcon

const PageFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
    </Filter>
)

export const PageList = props => (
    <List title="Pages" {...props} filters={<PageFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="title.en" label="Title" />
            <BooleanField source="published" />
            <DateField source="created_at" />
            <DateField source="updated_at" />
            <EditButton />
        </Datagrid>
    </List>
)

export const PageEdit = props => (
    <Edit title="Edit Page" undoable={false} {...props}>
        <SimpleForm>
            <TextInput source="title.en" label="English title" validate={required()} />
            <TextInput source="title.ar" label="Arabic title" validate={required()} />
            <TextInput multiline source="content.en" label="English content" validate={required()} />
            <TextInput multiline source="content.ar" label="Arabic content" validate={required()} />
            <BooleanInput source="published" />
        </SimpleForm>
    </Edit>
)

export const PageCreate = props => (
    <Create title="Create new page" {...props}>
        <SimpleForm>
            <TextInput source="title.en" label="English title" validate={required()} />
            <TextInput source="title.ar" label="Arabic title" validate={required()} />
            <TextInput source="content.en" multiline label="English content" validate={required()} />
            <TextInput source="content.ar" multiline label="Arabic content" validate={required()} />
            <BooleanInput source="published" />
        </SimpleForm>
    </Create>
)
