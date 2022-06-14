import React, { Component } from 'react'
import { Field } from 'react-final-form'
import { addField, Labeled } from 'react-admin'
import FontIconPicker from '@fonticonpicker/react-fonticonpicker'
import Icon from '@material-ui/core/Icon'
import MaterialIconList from './materialIcons'

import '@fonticonpicker/react-fonticonpicker/dist/fonticonpicker.base-theme.react.css'
import '@fonticonpicker/react-fonticonpicker/dist/fonticonpicker.material-theme.react.css'

const IconInput = ({ input, label, meta: { touched, error }}) => (
	<Labeled label={label}>
		<FontIconPicker
			icons={MaterialIconList}
			theme='bluegrey'
			isMulti={false}
			renderFunc={icon => <Icon>{icon}</Icon>}
			{...input}
		/>
	</Labeled>
)

export default addField(IconInput, { source: 'font_icon' })
