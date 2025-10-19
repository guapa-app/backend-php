import React, { useState, useEffect, useCallback } from 'react'
import PropTypes from 'prop-types'
import { TextInput } from 'react-admin'
import { withStyles } from '@material-ui/core/styles'
import Typography from '@material-ui/core/Typography'
import Modal from '@material-ui/core/Modal'
import Button from '@material-ui/core/Button'
import { useForm } from 'react-final-form'
import LocationInputMap from './map'

const styles = theme => ({
  paper: {
    backgroundColor: theme.palette.background.paper,
    boxShadow: theme.shadows[5],
    padding: theme.spacing(4),
  },
});

const LocationInput = ({ record, classes, source }) => {

	const [modalOpen, setModalOpen] = useState(false)
	
	const [initialCenter, setInitialCenter] = useState({
		lat: 30.04443,
		lng: 31.2357187,
	})

	const [center, setCenter] = useState({
		lat: 30.04443,
		lng: 31.2357187,
	})

	const form = useForm()

	useEffect(() => {
		if ( ! record.address) return
		const { lat, lng} = record.address
		if (lat && lng) {
			const location = {
				lat: Number(lat),
				lng: Number(lng),
			}
			setCenter(location)
		}
	}, [record.address])

	const getKey = useCallback(key => {
		return source ? `${source}.${key}` : key
	}, [source])

	const updateCenter = useCallback((lat, lng) => {
		if ( ! (lat && lng)) return
		const location = { lat, lng }
		setCenter(location)
		form.change(getKey('lat'), lat.toString())
		form.change(getKey('lng'), lng.toString())
	})

	const locationSelected = useCallback(() => {
		setModalOpen(false)
	}, [center])

	return (
		<div>
            <div>
            	<TextInput source={getKey('lat')} label="Latitude" disabled />
            	&nbsp;
            	<TextInput source={getKey('lng')} label="Longitude" disabled />
                &nbsp; &nbsp;
                <Button onClick={() => setModalOpen(true)}>
                	Select map location
                </Button>
                <br />
            </div>
            <Modal
	          aria-labelledby="Location select modal"
	          aria-describedby="Select location on the map"
	          open={modalOpen}
	          onClose={() => setModalOpen(false)}
	        >
	          <div className={classes.paper}>
	          	<div style={{position: 'relative'}}>
		          	<Typography variant="h6" id="modal-title" style={{color: 'white'}}>
		          	  <span>Select venue location from the map &nbsp; </span>
		              <Button style={{float: 'right'}} onClick={locationSelected}>
	                	Done
	                  </Button>
		            </Typography>
	            </div>
	            <br />
	            <div style={{height: '85vh', width: '100%'}}>
                    <LocationInputMap
                    	initialCenter={initialCenter}
                    	center={center}
                    	updateCenter={updateCenter}
                    />
                </div>
	          </div>
	        </Modal>
        </div>
    )
}

LocationInput.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(LocationInput);