import React, { useState, useEffect, useCallback } from 'react'
import { Link } from 'react-router-dom'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import Card from '@material-ui/core/Card'
import CardContent from '@material-ui/core/CardContent'
import FormControl from '@material-ui/core/FormControl'
import TextField from '@material-ui/core/TextField'
import CircularProgress from '@material-ui/core/CircularProgress'
import Button from '@material-ui/core/Button'
import SaveIcon from '@material-ui/icons/Save'
import { withStyles } from '@material-ui/core/styles'

import {
	Title, Create, required, SimpleForm, TextInput,
	useDataProvider, showNotification as showNotificationAction
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'

import SettingsIcon from '@material-ui/icons/Settings'

export const SettingIcon = SettingsIcon

const getSettingLabel = setting => {
	let label = setting.setting_key.toLowerCase().split('_').map(s => (
		s.charAt(0).toUpperCase() + s.substring(1))
	).join(' ')
	if (setting.setting_unit != null) {
		label = label + ' (' + setting.setting_unit + ')'
	}
	return label
}

export const SettingCreate = props => (
    <Create {...props}>
        <SimpleForm>
            <TextInput source="setting_key" />
            <TextInput multiline source="setting_value" />
            <TextInput source="setting_unit" />
            <TextInput multiline source="instructions" />
        </SimpleForm>
    </Create>
)

const styles = theme => {
	return {
		button: {
		    margin: theme.spacing(),
		},
		leftIcon: {
		    marginRight: theme.spacing(),
		},
	}
}

const SettingEditComponent = ({ showNotification, classes }) => {
	const [data, setData] = useState(null)
	const [loading, setLoading] = useState(true)
	const [submitting, setSubmitting] = useState(false)
	const [newData, setNewData] = useState({})
	const dataProvider = useDataProvider()

	useEffect(() => {
		dataProvider.getMany('settings', {
			ids: [0],
			filter: {
				all: true,
			},
		}).then(res => {
			setLoading(false)
			setData(res.data)
		}).catch(err => {
			showNotification('Error fetching settings', 'warning')
		})
	}, [])

	const saveSettings = useCallback(() => {
		setSubmitting(true)
		dataProvider.update('settings', { id: 0, data: { ...newData } }).then(res => {
			setSubmitting(false)
			showNotification('Settings Updated')
		}).catch(err => {
			setSubmitting(false)
			showNotification('An error occurred, try again', 'warning')
		})
	}, [JSON.stringify(newData)])

	return (
		<Card>
			<Title title="Application Settings" />
			<CardContent>
				{loading ?
					<div style={{dipslay: 'flex', flex: 1, textAlign: 'center'}}>
						<CircularProgress />
					</div>:
					(data && <form style={{maxWidth: 400}}>
							{data.map(setting => {
								return (
									<FormControl key={setting.setting_key} fullWidth>
										<TextField
											id={setting.setting_key}
											label={getSettingLabel(setting)}
											defaultValue={setting.setting_value}
											margin="normal"
											onChange={e => setNewData({ ...newData, [e.target.id]: e.target.value })}
											helperText={setting.instructions}
											variant="outlined"
										/>
									</FormControl>
								)
							})}
							<Button disabled={Object.keys(newData).length === 0 || submitting} variant="contained" color="primary" className={classes.button} onClick={saveSettings}>
						        <SaveIcon className={classes.leftIcon} />
						        Save
						    </Button>
						</form>
					)
				}
			</CardContent>
		</Card>
	)
}

SettingEditComponent.propTypes = {
    showNotification: PropTypes.func,
}

export const SettingEdit = connect(null, {
    showNotification: showNotificationAction,
})(withStyles(styles)(SettingEditComponent))
