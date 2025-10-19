import React from 'react'
import {
    List, Edit, Create, Delete, Datagrid, TextField,
    ChipField, EditButton, DisabledInput, required, SimpleForm,
    TextInput, Filter
} from 'react-admin'
import LocationCityIcon from '@material-ui/icons/LocationCity'

export const CityIcon = LocationCityIcon

const CityFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
    </Filter>
)

export const CityList = props => (
    <List title="Cities" {...props} filters={<CityFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="name.en" label="Name" />
            <EditButton />
        </Datagrid>
    </List>
)

const CityTitle = ({ record }) => {
    return <span>City: {record ? `"${record.name.en}"` : ''}</span>
}

export const CityEdit = props => (
    <Edit title="Edit city" undoable={false} {...props}>
        <SimpleForm>
            <TextInput disabled source="id" />
            <TextInput source="name.en" label="English city name" validate={required()} />
            <TextInput source="name.ar" label="Arabic city name" validate={required()} />
        </SimpleForm>
    </Edit>
)

export const CityCreate = props => (
    <Create {...props}>
        <SimpleForm>
            <TextInput source="name.en" label="English city name" validate={required()} />
            <TextInput source="name.ar" label="Arabic city name" validate={required()} />
        </SimpleForm>
    </Create>
)
