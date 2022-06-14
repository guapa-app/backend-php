import React from 'react'
import {
    List, Edit, Create, Delete, Datagrid, EmailField, TextField,
    ChipField, EditButton, SelectInput, required,
    SimpleForm, TabbedForm, FormTab, TextInput, Filter, DateField,
    ImageField, ImageInput, FunctionField, ReferenceInput,
    FormDataConsumer
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'
import LocationInput from '../customInputs/LocationInput'

import SupervisorAccountIcon from '@material-ui/icons/SupervisorAccount'

import { DateTimeInput, DateInput } from 'react-admin-date-inputs'
import moment from 'moment'
import MomentUtils from '@date-io/moment'

MomentUtils.prototype.getStartOfMonth = MomentUtils.prototype.startOfMonth


export const UserIcon = SupervisorAccountIcon

const UserFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <SelectInput label="Gender" source="gender" choices={[
            { id: 'Male', name: 'Male' },
            { id: 'Female', name: 'Female' },
            { id: 'Other', name: 'Other' },
        ]} />
        <SelectInput label="Status" source="status" choices={[
            { id: 'Active', name: 'Active' },
            { id: 'Closed', name: 'Closed' },
        ]} />
        <DateTimeInput source="startDate" label="Start Date" parse={v => v && v.format('YYYY-MM-DD HH:mm:ss')} options={{ format: 'DD/MM/YYYY, HH:mm:ss', ampm: false, clearable: true, disableFuture: true }} providerOptions={{utils: MomentUtils}} />
        <DateTimeInput source="endDate" label="End Date" parse={v => v && v.format('YYYY-MM-DD HH:mm:ss')} options={{ format: 'DD/MM/YYYY, HH:mm:ss', ampm: false, clearable: true, disableFuture: true }} providerOptions={{utils: MomentUtils}} />
    </Filter>
)

export const UserList = (props) => (
    <List title="Users" {...props} filters={<UserFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="name" />
            <EmailField source="email" />
            <TextField source="phone" />
            <ChipField source="status" label="Account" />
            <DateField source="created_at" />
            <EditButton />
        </Datagrid>
    </List>
)

const UserTitle = ({ record }) => {
    return <span>User&colon; {record ? `"${record.name}"` : ''}</span>;
}

export const UserEdit = (props) => (
    <Edit title="Edit User" undoable={false} {...props}>
        <TabbedForm>
            <FormTab label="Main details">
                <TextInput source="name" label="Nick name" validate={required()} />
                <TextInput source="email" type="email" label="Email address" />
                <TextInput source="phone" label="Phone number" validate={required()} />
                <SelectInput source="status" choices={[
                    { id: 'Active', name: 'Active' },
                    { id: 'Closed', name: 'Closed' },
                ]} validate={required()} />
            </FormTab>
            <FormTab label="Profile">
                <TextInput source="profile.firstname" label="First name" />
                <TextInput source="profile.lastname" label="Last name" />
                <SelectInput source="profile.gender" label="Gender" choices={[
                    { id: 'Male', name: 'Male' },
                    { id: 'Female', name: 'Female' },
                    { id: 'Other', name: 'Other' },
                ]} />
                <DateInput source="profile.birth_date" label="Date of birth" parse={v => v && moment(v.toISOString()).format('YYYY-MM-DD')} options={{ format: 'YYYY-MM-DD', ampm: false, clearable: true, disableFuture: true }} providerOptions={{utils: MomentUtils}} />
                <TextInput source="profile.about" label="About" multiline />
                <ImageInput source="profile.photo" label="Profile picture" accept="image/*" placeholder={<p>Drop your image here</p>}>
                    <ImageField source="large" title="file_name" />
                </ImageInput>
            </FormTab>
            {/*<FormTab label="Address">
                <ReferenceInput label="Country" source="address.country_id" reference="countries" validate={required()}>
                    <SelectInput optionText="name.en" optionValue="id" />
                </ReferenceInput>
                <FormDataConsumer>
                    {({ formData, ...rest }) => formData.address && formData.address.country_id && (
                        <ReferenceInput label="State" source="address.state_id" reference="states" validate={required()} filter={{country_id: formData.address.country_id}}>
                            <SelectInput optionText="name.en" optionValue="id" />
                        </ReferenceInput>
                    )}
                </FormDataConsumer>
                <FormDataConsumer>
                    {({ formData, ...rest }) => formData.address && formData.address.state_id && (
                        <ReferenceInput label="City" source="address.city_id" reference="cities" validate={required()} filter={{state_id: formData.address.state_id}}>
                            <SelectInput optionText="name.en" optionValue="id" />
                        </ReferenceInput>
                    )}
                </FormDataConsumer>
                <TextInput source="address.address_1" label="Address 1" validate={required()} />
                <TextInput source="address.address_2" label="Address 2" />
                <TextInput source="address.postal_code" label="Postal code" />
                <LocationInput />
            </FormTab>*/}
            <FormTab label="Password">
                <TextInput type="password" source="oldpassword" label="Your admin password" />
                <TextInput type="password" source="password" />
                <TextInput type="password" source="password_confirmation" label="Repeat password" />
            </FormTab>
        </TabbedForm>
    </Edit>
)

export const UserCreate = (props) => (
    <Create title="Create new user" {...props}>
        <TabbedForm>
            <FormTab label="Main details">
                <TextInput source="name" label="Nick name" validate={required()} />
                <TextInput source="email" type="email" label="Email address" />
                <TextInput source="phone" label="Phone number" validate={required()} />
                <SelectInput source="status" choices={[
                    { id: 'Active', name: 'Active' },
                    { id: 'Closed', name: 'Closed' },
                ]} validate={required()} />
            </FormTab>
            <FormTab label="Profile">
                <TextInput source="profile.firstname" label="First name" />
                <TextInput source="profile.lastname" label="Last name" />
                <SelectInput source="profile.gender" label="Gender" choices={[
                    { id: 'Male', name: 'Male' },
                    { id: 'Female', name: 'Female' },
                    { id: 'Other', name: 'Other' },
                ]} />
                <DateInput source="profile.birth_date" label="Date of birth" parse={v => v && moment(v.toISOString()).format('YYYY-MM-DD')} options={{ format: 'YYYY-MM-DD', ampm: false, clearable: true, disableFuture: true }} providerOptions={{utils: MomentUtils}} />
                <TextInput source="profile.about" label="About" multiline />
                <ImageInput source="profile.photo" label="Profile picture" accept="image/*" placeholder={<p>Drop your image here</p>}>
                    <ImageField source="src" title="file_name" />
                </ImageInput>
            </FormTab>
            {/*<FormTab label="Address">
                <ReferenceInput label="Country" source="address.country_id" reference="countries" validate={required()}>
                    <SelectInput optionText="name.en" optionValue="id" />
                </ReferenceInput>
                <FormDataConsumer>
                    {({ formData, ...rest }) => formData.address && formData.address.country_id && (
                        <ReferenceInput label="State" source="address.state_id" reference="states" validate={required()} filter={{country_id: formData.address.country_id}}>
                            <SelectInput optionText="name.en" optionValue="id" />
                        </ReferenceInput>
                    )}
                </FormDataConsumer>
                <FormDataConsumer>
                    {({ formData, ...rest }) => formData.address && formData.address.state_id && (
                        <ReferenceInput label="City" source="address.city_id" reference="cities" validate={required()} filter={{state_id: formData.address.state_id}}>
                            <SelectInput optionText="name.en" optionValue="id" />
                        </ReferenceInput>
                    )}
                </FormDataConsumer>
                <TextInput source="address.address_1" label="Address 1" validate={required()} />
                <TextInput source="address.address_2" label="Address 2" />
                <TextInput source="address.postal_code" label="Postal code" />
                <LocationInput />
            </FormTab>*/}
            <FormTab label="Password">
                <TextInput type="password" source="password" />
                <TextInput type="password" source="password_confirmation" label="Repeat password" />
            </FormTab>
        </TabbedForm>
    </Create>
)
