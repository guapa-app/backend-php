import React, {useEffect, useState} from 'react';
import { Create, Datagrid, FileField, FileInput, Filter,
    List, SelectArrayInput, SelectInput, SimpleForm,
    TextField, TextInput, useDataProvider,
} from 'react-admin'
import {withStyles} from '@material-ui/core/styles'
import StayCurrentPortraitIcon from '@material-ui/icons/StayCurrentPortrait'

export const NotificationIcon = StayCurrentPortraitIcon

const types = [
    {id: 'user', name: 'User'},
    {id: 'vendor', name: 'Vendor'},
    {id: 'android', name: 'Android'},
    {id: 'ios', name: 'iOS'},
]

const NotificationFilter = (props) => (
    <Filter {...props}>
        <TextInput label="Search" source="keyword" alwaysOn/>
        <SelectInput source="type" choices={types} alwaysOn/>
    </Filter>
)

export const NotificationList = (props) => (
    <List title="Notifications" {...props} filters={<NotificationFilter/>}>
        <Datagrid>
            <TextField source="id"/>
            <TextField source="data.title" label="Title"/>
            <TextField source="data.summary" label="summary"/>
        </Datagrid>
    </List>
)

const NotificationTitle = ({record}) => {
    return <span>Notification {record ? `"${record.name}"` : ''}</span>;
}

const styles = {
    card: {
        overflow: 'visible',
    },
}

const NotificationCreateComponent = props => {
    const [recipientOptions, setRecipientOptions] = useState([]);
    const [selectedType, setSelectedType] = useState('');
    const dataProvider = useDataProvider();

    useEffect(() => {
        const fetchRecipients = async () => {
            if (selectedType === 'user' || selectedType === 'vendor') {
                try {
                    const {data} = await dataProvider.getList(`notifications/${selectedType}s`, {
                        pagination: {
                            page: 1,
                            perPage: 10000
                        }, sort: {field: 'name', order: 'ASC'}
                    });
                    setRecipientOptions(data.map(recipient => ({id: recipient.id, name: recipient.name})));
                } catch (error) {
                    console.error(`Error fetching ${selectedType}s:`, error);
                }
            } else {
                setRecipientOptions([]);
            }
        };

        fetchRecipients();
    }, [selectedType, dataProvider]);

    const handleTypeChange = event => {
        setSelectedType(event.target.value);
    };

    return (
        <Create {...props}>
            <SimpleForm>
                <TextInput source="title" label="Title"/>
                <TextInput source="summary" label="Summary"/>
                <SelectInput source="type" label="Type" choices={types} onChange={handleTypeChange}/>
                {(selectedType === 'user' || selectedType === 'vendor') && (
                    <SelectArrayInput source="recipients" choices={recipientOptions} optionText="name"/>
                )}
                <FileInput source="image" label="Related image" accept="image/*">
                    <FileField source="src" title="title"/>
                </FileInput>
            </SimpleForm>
        </Create>
    );
};
export const NotificationCreate = withStyles(styles)(NotificationCreateComponent)
