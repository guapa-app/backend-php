import React, { useState, useCallback } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import { useDataProvider } from 'react-admin'

import { showNotification as showNotificationAction } from 'react-admin'
import { push as pushAction } from 'connected-react-router'

import classnames from 'classnames'

import { withStyles, createStyles } from '@material-ui/core/styles'
import Button from '@material-ui/core/Button'
import CircularProgress from '@material-ui/core/CircularProgress'
import CheckIcon from '@material-ui/icons/Check'
import CloseIcon from '@material-ui/icons/Close'

const styles = ({ spacing }) =>
    createStyles({
        button: {
            position: 'relative',
        },
        leftIcon: {
            marginRight: spacing(1),
        },
        icon: {
            fontSize: 18,
        },
    });


const MarkReadButton = ({ record, showNotification, classes, ...rest }) => {

    const [submitting, setSubmitting] = useState(false)
    const dataProvider = useDataProvider()

    const handleClick = useCallback(() => {
        setSubmitting(true)
        dataProvider.update('messages', { id: record.id, data: { read: record.read_at ? '0' : '1' } })
            .then(() => {
                setSubmitting(false)
                showNotification('Message marked as ' + (record.read_at ? 'unread' : 'read'));
            })
            .catch((e) => {
                setSubmitting(false)
                showNotification('Error: Cannot mark message as ' + (record.read_at ? 'unread' : 'read'), 'warning')
            });
    }, [record?.read_at])

    if ( ! record) {
        return null
    }

    
    return (
        <Button
            onClick={handleClick}
            color={'default'}
            variant="outlined"
            disabled={submitting}
        >
            {submitting ? (
                <CircularProgress
                    size={18}
                    thickness={2}
                    className={classes.leftIcon}
                />
            ) : (
                record.read_at ?
                    <CloseIcon className={classnames(classes.leftIcon, classes.icon)} />:
                    <CheckIcon className={classnames(classes.leftIcon, classes.icon)} />
                
            )}
            {record.read_at ? 'Unread' : 'Read'}
        </Button>
    )
}

MarkReadButton.propTypes = {
    record: PropTypes.object,
    showNotification: PropTypes.func,
    dataProvider: PropTypes.object,
};

export default connect(null, {
    showNotification: showNotificationAction,
})(withStyles(styles)(MarkReadButton));