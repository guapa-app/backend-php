import React, { Component } from 'react'
import { Field } from 'react-final-form'
import { addField, Labeled } from 'react-admin'
import { Rating } from 'material-ui-rating'

const RatingStarsInput = ({ input, label, meta: { touched, error }}) => (
	<Labeled label={label}>
		<Rating
			max={5}
			{...input}
		/>
	</Labeled>
)

export default addField(RatingStarsInput, { source: 'stars' })