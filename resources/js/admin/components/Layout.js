/**
 * Using custom layout to be able to apply the custom menu
 * 
 */

import React from 'react'
import { Layout } from 'react-admin'
import MyMenu from './Menu'
import MyAppBar from './AppBar'

import { withStyles } from '@material-ui/core/styles'

import { connect } from 'react-redux'

import classnames from 'classnames'

const styles = {
	content: {
		// padding: 0,
	},
	layout: {
		'& main > div:last-child': {
			padding: 0,
			paddingLeft: 5,
		}
	}
}

const MyLayout = ({ classes, dispatch, pathname, ...props }) => (
	<Layout
		{...props}
		className={classnames({[classes.layout]: pathname === '/' || pathname === '/dashboard' })}
		menu={MyMenu}
		appBar={MyAppBar}
	/>
)


const mapStateToProps = state => {
	return {
		pathname: state.router.location.pathname,
	}	
}

export default connect(mapStateToProps)(withStyles(styles)(MyLayout))