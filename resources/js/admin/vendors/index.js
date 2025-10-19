import React from 'react'
import {
    List, Edit, Create, Delete, Datagrid, EmailField, TextField,
    ChipField, EditButton, SelectInput, required, ReferenceArrayInput,
    TabbedForm, FormTab, TextInput, Filter, DateField, SelectArrayInput,
    ImageField, ImageInput, FunctionField, ReferenceInput, Show,
    FormDataConsumer, BooleanInput, BooleanField, ReferenceManyField,
    TabbedShowLayout, Tab, ShowButton, RichTextField, ReferenceField,
    SimpleFormIterator, ArrayInput
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'
import StaffInput from '../customInputs/StaffInput'
// import LocationInput from '../customInputs/LocationInput'
import Chip from '@material-ui/core/Chip'
import SupervisorAccountIcon from '@material-ui/icons/SupervisorAccount'

import LocationInput from '../customInputs/LocationInput'
import AddAddressButton from '../components/AddAddressButton'

import { AddressTypes, VendorTypes, WeekDays } from '../utils/constants'

import { DateTimeInput, DateInput } from 'react-admin-date-inputs'
import moment from 'moment'
import MomentUtils from '@date-io/moment'

MomentUtils.prototype.getStartOfMonth = MomentUtils.prototype.startOfMonth

export const VendorIcon = SupervisorAccountIcon

const VendorFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <SelectInput label="Status" source="status" choices={[
            { id: '1', name: 'Active' },
            { id: '0', name: 'Closed' },
        ]} />
        <SelectInput label="Verification" source="verified" choices={[
            { id: 1, name: 'Verified' },
            { id: 0, name: 'Unverified' },
        ]} />
        <SelectInput source="type" choices={VendorTypes} />
        <DateTimeInput source="startDate" label="Start Date" parse={v => v && v.format('YYYY-MM-DD HH:mm:ss')} options={{ format: 'DD/MM/YYYY, HH:mm:ss', ampm: false, clearable: true, disableFuture: true }} providerOptions={{utils: MomentUtils}} />
        <DateTimeInput source="endDate" label="End Date" parse={v => v && v.format('YYYY-MM-DD HH:mm:ss')} options={{ format: 'DD/MM/YYYY, HH:mm:ss', ampm: false, clearable: true, disableFuture: true }} providerOptions={{utils: MomentUtils}} />
    </Filter>
)

export const VendorList = props => (
    <List title="Vendors" {...props} filters={<VendorFilter />}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="name" />
            <EmailField source="email" />
            <TextField source="phone" />
            <FunctionField source="status" render={record => {
                return <Chip label={record.status == '1' ? 'Active' : 'Closed'} />
            }} />
            <BooleanField source="verified" />
            <TextField source="users_count" label="Staff" />
            <FunctionField source="type" render={({ type }) => {
                const t = VendorTypes.find(vendorType => vendorType.id === type)
                return t && t.name
            }} />
            <DateField source="created_at" />
            <ShowButton />
            <EditButton />
        </Datagrid>
    </List>
)

const VendorTitle = ({ record }) => {
    return <span>User&colon; {record ? `"${record.name}"` : ''}</span>;
}

export const VendorEdit = props => (
    <Edit title="Edit vendor" undoable={false} {...props}>
        <TabbedForm>
            <FormTab label="Vendor details">
                <TextInput source="name" validate={required()} />
                <TextInput source="email" type="email" label="Email address" validate={required()} />
                <TextInput source="phone" label="Phone number" validate={required()} />
                <ReferenceArrayInput label="Specialties" source="specialty_ids" reference="taxonomies" filter={{parents: 1, type: 'specialty'}}>
                    <SelectArrayInput optionText="title.en" />
                </ReferenceArrayInput>
                <SelectInput source="status" choices={[
                    { id: '1', name: 'Active' },
                    { id: '0', name: 'Closed' },
                ]} validate={required()} />
                <BooleanInput source="verified" />
                <SelectInput source="type" choices={VendorTypes} validate={required()} />
                <TextInput source="about" multiline />
                <SelectArrayInput source="work_days" choices={WeekDays} />
                <TextInput source="working_days" />
                <TextInput source="working_hours" />
                <ImageInput source="logo" label="Logo" accept="image/*" placeholder={<p>Drop your image here</p>}>
                    <ImageField source="large" title="file_name" />
                </ImageInput>
            </FormTab>
            <FormTab label="Staff">
                <StaffInput />
            </FormTab>
            <FormTab label="Social">
                <TextInput source="whatsapp" />
                <TextInput source="twitter" />
                <TextInput source="instagram" />
            </FormTab>
            <FormTab label="Appointments">
                <ArrayInput source="appointments">
                    <SimpleFormIterator>
                        <TextInput source="from_time" label="From time" validate={required()} />
                        <TextInput source="to_time" label="To time" validate={required()} />
                    </SimpleFormIterator>
                </ArrayInput>
            </FormTab>
        </TabbedForm>
    </Edit>
)

export const VendorCreate = props => (
    <Create title="Create new vendor" {...props}>
        <TabbedForm>
            <FormTab label="Vendor details">
                <TextInput source="name" validate={required()} />
                <TextInput source="email" type="email" label="Email address" validate={required()} />
                <TextInput source="phone" label="Phone number" validate={required()} />
                <ReferenceArrayInput label="Specialties" source="specialty_ids" reference="taxonomies" filter={{parents: 1, type: 'specialty'}}>
                    <SelectArrayInput optionText="title.en" />
                </ReferenceArrayInput>
                <SelectInput source="status" choices={[
                    { id: '1', name: 'Active' },
                    { id: '0', name: 'Closed' },
                ]} validate={required()} />
                <BooleanInput source="verified" />
                <SelectInput source="type" choices={VendorTypes} validate={required()} />
                <TextInput source="about" multiline />
                <SelectArrayInput source="work_days" choices={WeekDays} />
                <ArrayInput source="appointments">
                    <SimpleFormIterator>
                        <TextInput source="from_time" label="From time" validate={required()} />
                        <TextInput source="to_time" label="To time" validate={required()} />
                    </SimpleFormIterator>
                </ArrayInput>
                <TextInput source="working_days" />
                <TextInput source="working_hours" />
                <ImageInput source="logo" label="Logo" accept="image/*" placeholder={<p>Drop your image here</p>}>
                    <ImageField source="src" title="file_name" />
                </ImageInput>
            </FormTab>
            <FormTab label="Address">
                <SelectInput source="address.type" choices={AddressTypes} />
                <ReferenceInput label="City" source="address.city_id" reference="cities" validate={required()}>
                    <SelectInput optionText="name.en" optionValue="id" />
                </ReferenceInput>
                <TextInput source="address.address_1" label="Address 1" validate={required()} />
                <TextInput source="address.address_2" label="Address 2" />
                <TextInput source="address.postal_code" label="Postal code" />
                <LocationInput source="address" />
            </FormTab>
            <FormTab label="Staff">
                <StaffInput />
            </FormTab>
            <FormTab label="Social">
                <TextInput source="whatsapp" />
                <TextInput source="twitter" />
                <TextInput source="instagram" />
            </FormTab>
            <FormTab label="Appointments">
                <ArrayInput source="appointments">
                    <SimpleFormIterator>
                        <TextInput source="from_time" label="From time" validate={required()} />
                        <TextInput source="to_time" label="To time" validate={required()} />
                    </SimpleFormIterator>
                </ArrayInput>
            </FormTab>
        </TabbedForm>
    </Create>
)

export const VendorShow = ({ hasShow, ...props }) => (
    <Show {...props}>
        <TabbedShowLayout>
            <Tab label="Details">
                <TextField source="id" />
                <TextField source="name" />
                <TextField source="phone" />
                <TextField source="email" />
                <RichTextField source="about" />
                <FunctionField source="status" render={record => record && (
                    record.status == '1' ? 'Active' : 'Closed'
                )} />
                <FunctionField source="verified" render={record => record && (
                    record.verified ? 'Verified' : 'Unverified'
                )} />
                <FunctionField source="type" render={({ type }) => {
                    const t = VendorTypes.find(vendorType => vendorType.id === type)
                    return t && t.name
                }} />
                <TextField source="working_days" />
                <TextField source="working_hours" />
                <TextField source="created_at" />
                <TextField source="updated_at" />
            </Tab>
            <Tab label="Addresses">
                <ReferenceManyField source="id" target="vendor_id" reference="addresses">
                    <Datagrid>
                        <TextField source="id" />
                        <ReferenceField source="city_id" reference="cities">
                            <TextField source="name.en" />
                        </ReferenceField>
                        <FunctionField source="type" render={({ type }) => {
                            const t = AddressTypes.find(addressType => addressType.id === type)
                            return t && t.name
                        }} />
                        <TextField source="address_1" />
                        <TextField source="postal_code" />
                        <DateField source="created_at" />
                        <DateField source="updated_at" />
                        <ShowButton />
                        <EditButton />
                    </Datagrid>
                </ReferenceManyField>
                <AddAddressButton type="vendor" />
            </Tab>
            <Tab label="Social">
                <TextField source="whatsapp" />
                <TextField source="twitter" />
                <TextField source="instagram" />
            </Tab>
        </TabbedShowLayout>
    </Show>
)