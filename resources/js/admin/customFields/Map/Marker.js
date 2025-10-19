import React from 'react'
import RoomIcon from '@material-ui/icons/Room'

import { withStyles } from '@material-ui/core/styles'

const styles = {
	root: {
		fontSize: 40,
	}
}

export default withStyles(styles)(({ classes }) => (
	<RoomIcon color="primary" className={classes.root} />
))