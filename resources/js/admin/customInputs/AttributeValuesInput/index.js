import React, { useState, useEffect, useCallback } from 'react'
import PropTypes from 'prop-types'

import { useField } from 'react-final-form'

import { TextInput, NumberInput, required } from 'react-admin'

import Fab from '@material-ui/core/Fab'
import Table from '@material-ui/core/Table'
import TableBody from '@material-ui/core/TableBody'
import TableCell from '@material-ui/core/TableCell'
import TableHead from '@material-ui/core/TableHead'
import TableRow from '@material-ui/core/TableRow'
import red from '@material-ui/core/colors/red'
import { withStyles } from '@material-ui/core/styles'

import AddIcon from '@material-ui/icons/Add'
import DeleteIcon from '@material-ui/icons/Delete'

const styles = theme => ({
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
})

export const AttributeValuesInput = ({ record, classes }) => {

	// const [values, setValues] = useState(record.values || []);

	const { input, meta } = useField('values')
	const typeInput = useField('type')

	const valuesString = JSON.stringify(input.value)

	useEffect(() => {
		if (record.id && record.values && record.values.length > 0) {
			record.values = record.values.map(value => value.attribute_value)
			input.onChange(record.values)
		} else {
			input.onChange([{
				en: null,
				ar: null,
			}])
		}
	}, [Object.keys(record).length])

	const addValue = useCallback(() => {
		input.onChange([
			...input.value,
			{
				en: null,
				ar: null,
			}
		])
	}, [valuesString])

	const deleteValue = useCallback((index) => {
		const newValues = [...input.value]
		newValues.splice(index, 1)
		input.onChange(newValues)
	}, [valuesString])

	const isString = typeInput.input.value === 'string'

	return (
		<div className={classes.root}>
			<Fab color="primary" aria-label="Add" className={classes.addButton} onClick={addValue}>
        		<AddIcon />
      		</Fab>
      		<Table className={classes.table} size="small" aria-label="Tickets table">
		        <TableHead>
		          <TableRow>
		            <TableCell className={classes.tableCell}>#</TableCell>
		            <TableCell className={classes.tableCell}>{isString ? 'English' : 'Value'}</TableCell>
		            {isString ?
		            	<TableCell className={classes.tableCell}>Arabic</TableCell>:
		            	null
		            }
		          </TableRow>
		        </TableHead>
		        <TableBody>
		        	{input.value ? input.value.map((value, index) => (
		        		<TableRow key={'value-' + index}>
			            	<TableCell className={classes.tableCell} component="th" scope="row">{index + 1}</TableCell>
			              	<TableCell className={classes.tableCell}>
			              		{isString ?
			                		<TextInput source={'values['+index+'][en]'} label=""  className={classes.titleCell} validate={required()} /> :
			                		<NumberInput source={'values['+index+'][en]'} label=""  className={classes.titleCell} validate={required()} />
			              		}
			              	</TableCell>
			              	{isString ?
				              	<TableCell className={classes.tableCell}>
				              		<TextInput source={'values['+index+'][ar]'} label="" className={classes.titleCell} />
				              	</TableCell>:
				              	null
				            }
				            <TableCell className={classes.tableCell} align="right">
			              		<DeleteIcon className={classes.deleteButton} onClick={deleteValue.bind(null, index)} />
			              	</TableCell>
			            </TableRow>
		        	)) : null}
		        </TableBody>
		    </Table>
		</div>
	)
}

AttributeValuesInput.propTypes = {
  classes: PropTypes.object.isRequired,
}

export default withStyles(styles)(AttributeValuesInput)
