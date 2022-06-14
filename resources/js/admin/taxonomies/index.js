import React from 'react'
import {
    List, Edit, Create, Delete, Datagrid, TextField,
    ChipField, EditButton, required, SimpleForm,
    TextInput, Filter, SelectInput, FunctionField,
    ImageInput, ImageField, ReferenceInput,
    FormDataConsumer
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'

import IconInput from '../customInputs/IconInput'

import { withStyles } from '@material-ui/core/styles'
import Icon from '@material-ui/core/Icon'
import CategoryIcon from '@material-ui/icons/Category'

export const TaxonomyIcon = CategoryIcon

const TaxTypes = [
    {id: 'category', name: 'Category'},
    {id: 'tag', name: 'Tag'},
    {id: 'specialty', name: 'Specialty'},
    {id: 'blog_category', name: 'Blog category'},
]

const TaxonomyFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn />
        <SelectInput source="type" choices={TaxTypes} alwaysOn />
    </Filter>
)

export const TaxonomyList = (props) => (
    <List title="Taxonomies" {...props} filters={<TaxonomyFilter />}>
        <Datagrid>
            <TextField source="id" />
            <FunctionField label="Icon" render={record => record.font_icon &&
                <Icon>{record.font_icon}</Icon>
            } />
            <TextField source="title.en" label="Title" />
            <FunctionField label="Type" render={({ type }) => {
                const t = TaxTypes.find(TaxType => TaxType.id === type)
                return t && t.name
            }} />
            <TextField source="parent.title.en" label="Parent" />
            <EditButton />
        </Datagrid>
    </List>
)

const TaxonomyTitle = ({ record }) => {
    return <span>Taxonomy {record ? `"${record.name}"` : ''}</span>;
}

const styles = {
    card: {
        overflow: 'visible',
    },
}

const MySelectInput = ({ type, choices, ...props }) => {
    return <SelectInput {...props} optionText="title.en" choices={choices ? choices.filter(tax => tax.type === type) : []} />
}

const TaxonomyEditComponent = props => (
    <Edit title="Edit Taxonomy" undoable={false} {...props}>
        <SimpleForm>
            <TextInput disabled source="id" />
            <TextInput source="title.en" label="English title" validate={required()} />
            <TextInput source="title.ar" label="Arabic title" validate={required()} />
            <TextInput source="description.en" label="English description" multiline />
            <TextInput source="description.ar" label="Arabic description" multiline />
            <SelectInput source="type" choices={TaxTypes} validate={required()} />
            <FormDataConsumer>
                {({ formData, ...rest }) => formData.type === 'category' &&
                    <ReferenceInput label="Parent" source="parent_id" reference="taxonomies" filter={{ type: formData.type }} allowEmpty>
                        <MySelectInput type={formData.type} />
                    </ReferenceInput>
                }
            </FormDataConsumer>
            <IconInput label="Font icon" />
            <ImageInput source="icon" label="Icon" accept="image/*" placeholder={<p>Drop your image here</p>}>
                <ImageField source="url" title="file_name" />
            </ImageInput>
        </SimpleForm>
    </Edit>
)

const TaxonomyCreateComponent = props => (
    <Create {...props}>
        <SimpleForm>
            <TextInput source="title.en" label="English title" validate={required()} />
            <TextInput source="title.ar" label="Arabic title" validate={required()} />
            <TextInput source="description.en" label="English description" multiline />
            <TextInput source="description.ar" label="Arabic description" multiline />
            <SelectInput source="type" choices={TaxTypes} validate={required()} />
            <FormDataConsumer>
                {({ formData, ...rest }) => formData.type === 'category' &&
                    <ReferenceInput label="Parent" source="parent_id" reference="taxonomies" filter={{ type: formData.type }} allowEmpty>
                        <MySelectInput type={formData.type} />
                    </ReferenceInput>
                }
            </FormDataConsumer>
            <IconInput label="Font icon" />
            <ImageInput source="icon" label="Icon" accept="image/*" placeholder={<p>Drop your image here</p>}>
                <ImageField source="src" title="file_name" />
            </ImageInput>
        </SimpleForm>
    </Create>
)

export const TaxonomyCreate = withStyles(styles)(TaxonomyCreateComponent)

export const TaxonomyEdit = withStyles(styles)(TaxonomyEditComponent)
