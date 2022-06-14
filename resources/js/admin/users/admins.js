import React from 'react'
import {
    List, Edit, Create, Delete, Datagrid, EmailField, TextField,
    ChipField, EditButton, required, SimpleForm,
    TabbedForm, FormTab, TextInput, SelectInput, Filter
} from 'react-admin'
import PersonIcon from '@material-ui/icons/AccountCircle'

export const AdminIcon = PersonIcon

const AdminFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
    </Filter>
)

export const AdminList = (props) => (
    <List title="Admins" {...props} filters={<AdminFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="name" />
            <EmailField source="email" />
            <ChipField source="role" />
            <EditButton />
        </Datagrid>
    </List>
)

const AdminTitle = ({ record }) => {
    return <span>Admin {record ? `"${record.name}"` : ''}</span>;
}

export const AdminEdit = (props) => (
    <Edit title="Edit Admin" undoable={false} {...props}>
        <TabbedForm>
            <FormTab label="Basic information">
                <TextInput disabled source="id" />
                <TextInput source="name" label="Full name" validate={required()} />
                <TextInput source="email" type="email" label="Email address" validate={required()} />
                <SelectInput source="role" defaultValue="admin" choices={[
                    { id: 'admin', name: 'Admin' },
                    { id: 'moderator', name: 'Moderator' },
                ]} validate={required()} />
            </FormTab>
            <FormTab label="Edit password">
                <TextInput source="adminpassword" type="password" label="Your admin password" required />
                <TextInput source="password" type="password" label="New password" required />
                <TextInput source="password_confirmation" type="password" label="Confirm password" required />
            </FormTab>
        </TabbedForm>
    </Edit>
)

export const AdminCreate = (props) => (
    <Create {...props}>
        <SimpleForm>
            <TextInput source="name" validate={required()} />
            <TextInput source="email" type="email" validate={required()} />
            <TextInput source="password" type="password" validate={required()} />
            <SelectInput source="role" defaultValue="admin" choices={[
                { id: 'admin', name: 'Admin' },
                { id: 'moderator', name: 'Moderator' },
            ]} validate={required()} />
        </SimpleForm>
    </Create>
)
