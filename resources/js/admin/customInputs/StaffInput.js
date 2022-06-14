import React, { useCallback, useState, useEffect } from 'react'

import {
    TextInput, NumberInput, BooleanInput, ReferenceInput,
    withDataProvider, AutocompleteInput, SelectInput, required
} from 'react-admin'

import { useForm, useField } from 'react-final-form'

import _ from 'lodash'

import { makeStyles } from '@material-ui/core/styles'
import Fab from '@material-ui/core/Fab'
import Table from '@material-ui/core/Table'
import TableBody from '@material-ui/core/TableBody'
import TableCell from '@material-ui/core/TableCell'
import TableHead from '@material-ui/core/TableHead'
import TableRow from '@material-ui/core/TableRow'
import red from '@material-ui/core/colors/red'

import AddIcon from '@material-ui/icons/Add'
import DeleteIcon from '@material-ui/icons/Delete'

const useStyles = makeStyles(theme => ({
    root: {
        position: 'relative',
        paddingTop: 24,
    },
    table: {
        width: '100%',
        marginTop: 40,
    },
    addButton: {
        margin: theme.spacing(),
        position: 'absolute',
        right: 0,
        top: 0,
    },
    deleteButton: {
        margin: theme.spacing(),
        cursor: 'pointer',
        color: red[400],
    },
    extendedIcon: {
        marginRight: theme.spacing(),
    },
    titleCell: {
        maxWidth: 200,
    },
    descriptionCell: {
        minWidth: 150,
        maxWidth: 200,
    },
    tableCell: {
        paddingRight: 10,
        paddingLeft: 10,
    },
    numericCell: {
        maxWidth: 80,
    },
}))

const staffRoles = [
    {
        id: 'manager',
        name: 'Manager',
    },
    {
        id: 'doctor',
        name: 'Doctor',
    },
]

const StaffInputComponent = ({ dataProvider, record }) => {

    const classes = useStyles()
    const form = useForm()
    const { input, meta } = useField('staff')

    useEffect(() => {
        if (record.id && record.staff && record.staff.length > 0) {
            input.onChange(record.staff.map(staff => staff.pivot))
        } else {
            input.onChange([
                {
                    user_id: null,
                    role: 'doctor',
                    email: null,
                }
            ])
        }
    }, [Object.keys(record).length])
    
    const staffString = JSON.stringify(input.value)

    const addStaff = useCallback(() => {
        input.onChange([
            ...input.value, {
                user_id: null,
                role: 'doctor',
                email: null,
            },
        ])
    }, [staffString])

    const deleteStaff = useCallback((index) => {
        const newStaff = [...input.value]
        newStaff.splice(index, 1)
        input.onChange(newStaff)
    }, [staffString])

    return (
        <div className={classes.root}>
            <Fab color="primary" aria-label="Add" className={classes.addButton} onClick={addStaff}>
                <AddIcon />
            </Fab>
            <Table className={classes.table} size="small" aria-label="Staff table">
                <TableHead>
                  <TableRow>
                    <TableCell className={classes.tableCell}>#</TableCell>
                    <TableCell className={classes.tableCell}>User</TableCell>
                    <TableCell className={classes.tableCell}>Role</TableCell>
                    <TableCell className={classes.tableCell}>Email</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                    {input.value && input.value.map((employee, index) => (
                        <TableRow key={'staff-' + index}>
                            <TableCell className={classes.tableCell} component="th" scope="row">{index + 1}</TableCell>
                            <TableCell className={classes.tableCell}>
                                <ReferenceInput reference="users" target="id" source={`staff[${index}][user_id]`} label="User" validate={required()}>
                                    <AutocompleteInput source="id" optionText={user => user.name + ' - ' + user.phone} optionValue="id" />
                                </ReferenceInput>
                            </TableCell>
                            <TableCell className={classes.tableCell}>
                                <SelectInput source={`staff[${index}][role]`} label="Role" validate={required()} choices={staffRoles} />
                            </TableCell>
                            <TableCell className={classes.tableCell}>
                                <TextInput source={`staff[${index}][email]`} label="Email (optional)" />
                            </TableCell>
                            <TableCell className={classes.tableCell} align="right">
                                <DeleteIcon className={classes.deleteButton} onClick={deleteStaff.bind(null, index)} />
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </div>
    )
}

export default withDataProvider(StaffInputComponent)
