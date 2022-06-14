import React from 'react'
import { Link } from 'react-router-dom'
import LocationOnIcon from '@material-ui/icons/LocationOn'
import { withStyles } from '@material-ui/core/styles'
import { Button } from 'react-admin'

const styles = {
  button: {
    marginTop: '1em',
    marginBottom: '1em',
  }
}

const AddAddressButton = ({ classes, record, type }) => (
  <Button
    className={classes.button}
    variant="contained"
    component={Link}
    to={`/addresses/create?addressable_id=${record.id}&addressable_type=${type}`}
    label="Add new address"
    title="Add new address"
  >
    <LocationOnIcon />
  </Button>
)

export default withStyles(styles)(AddAddressButton)
