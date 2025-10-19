import React, { Fragment, useState, useEffect } from 'react'

import {
    TextInput, NumberInput, BooleanInput,
    withDataProvider
} from 'react-admin'

import RichTextInput from 'ra-input-rich-text'

import { useForm, useField } from 'react-final-form'

import _ from 'lodash'

// import Snackbar from '@material-ui/core/Snackbar'
import SnackbarContent from '@material-ui/core/SnackbarContent'
import { amber } from '@material-ui/core/colors'
import { makeStyles } from '@material-ui/core/styles'

import { DateTimeInput, DateInput } from 'react-admin-date-inputs'
import moment from 'moment'
import MomentUtils from '@date-io/moment'
MomentUtils.prototype.getStartOfMonth = MomentUtils.prototype.startOfMonth

const useStyles = makeStyles(theme => ({
    attributeInput: {
        width: '240px',
    },
    attributeTextInput: {
        width: '400px',
    },
    snackbarContent: {
        backgroundColor: amber[700],
        display: 'inline-block',
        width: 'auto',
        boxShadow: 'none',
    },
    message: {

    }
}))

const getAttributeInput = (attribute, form, className) => {

    if (attribute.type === 'string') {
        return (
            <TextInput
                className={className}
                label={attribute.title.en}
                source={'attributes.'+attribute.slug}
            />
        )
    } else if (['decimal', 'integer', 'year', 'foreignId'].indexOf(attribute.type) > -1) {
        return (
            <NumberInput
                className={className}
                label={attribute.title.en}
                source={'attributes.'+attribute.slug}
                onChange={e => {
                    attribute.type !== 'decimal' &&
                        form.change('attributes.'+attribute.slug, parseInt(e.target.value))
                }}
            />
        )
    } else if (attribute.type === 'text') {
        return (
            <RichTextInput
                className={className}
                label={attribute.title.en}
                source={'attributes.'+attribute.slug}
            />
        )
    } else if (attribute.type === 'boolean') {
        return <BooleanInput source={'attributes.'+attribute.slug} />
    } else if (attribute.type === 'date') {
        return (
            <DateInput
                source={'attributes.'+attribute.slug}
                label={attribute.title.en}
                parse={v => v && v.format('YYYY-MM-DD')}
                options={{ format: 'DD/MM/YYYY', ampm: false, clearable: true, disableFuture: true }}
                providerOptions={{utils: MomentUtils}}
            />
        )
    } else if (attribute.type === 'dateTime') {
        return (
            <DateTimeInput
                source={'attributes.'+attribute.slug}
                label={attribute.title.en}
                parse={v => v && v.format('YYYY-MM-DD HH:mm:ss')}
                options={{ format: 'DD/MM/YYYY, HH:mm:ss', ampm: false, clearable: true, disableFuture: true }}
                providerOptions={{utils: MomentUtils}}
            />
        )
    }
}

const AttributesInputComponent = ({ dataProvider, record }) => {

    const classes = useStyles()
    const form = useForm()
    const [attributes, setAttributes] = useState([])
    const { input, meta } = useField('selectedCategories')

    const selectedCategories = input.value

    const getAttributes = () => {
        dataProvider.getMany('attributes', {
            // Fake ids for react-admin to send the request
            ids: [0],
            filter: {
                // Instruct rest client to not apply ids filter
                all: true,
                category_id: selectedCategories[0],
            }
        }).then(res => {
            setAttributes(res.data)
        }).catch(err => {
            alert('Failed to fetch categories')
        })
    }

    useEffect(() => {
        if ( ! selectedCategories[0]) {
            return
        }

        getAttributes()
    }, [selectedCategories[0]])
    
    if ( ! selectedCategories || ! selectedCategories[0]) {
        return (
            <SnackbarContent
              className={classes.snackbarContent}
              aria-describedby="client-snackbar"
              message={
                <span id="client-snackbar" className={classes.message}>
                  Please select the category first
                </span>
              }              
            />
        )
    }

    return (
        <Fragment>
            {attributes.map(attribute => (
                <div key={`attribute-${attribute.id}`}>
                    {getAttributeInput(attribute, form,
                        attribute.type === 'text' ? classes.attributeTextInput : classes.attributeInput)}
                </div>
            ))}
        </Fragment>
    )
}

export default withDataProvider(AttributesInputComponent)
