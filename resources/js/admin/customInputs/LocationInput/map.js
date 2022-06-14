import React, { Component } from 'react'
import GoogleMap from 'google-map-react'
import Marker from '../../customFields/Map/Marker'

class LocationInputMap extends Component {
	constructor(props) {
		super(props)
		this.state = {

		}

		this.onChildClick = this.onChildClick.bind(this)
		this.onGoogleApiLoaded = this.onGoogleApiLoaded.bind(this)
	}

	onMapClick(mapProps, map, clickEvent) {
		this.props.updateCenter(clickEvent.latLng)
	}

	onGoogleApiLoaded({ map, maps }) {
		this.map = map
		this.maps = maps
		this.mapClickListener = map.addListener('click', event => {
		    this.props.updateCenter(event.latLng.lat(), event.latLng.lng())
		})
	}

	onChildClick(key, childProps) {
		// console.log(key, childProps)
		// this.props.updateCenter(childProps.lat, childProps.lng)
  	}

  	componentWillUnmount() {
  		if (this.maps) {
  			this.maps.event.removeListener(this.mapClickListener)
  		}
  	}

	render() {
		const { center: { lat, lng }, initialCenter } = this.props
		if ( ! initialCenter.lat) { return null}
		return (
			<div style={{
					width: '100%',
					height: '83vh',
			}}>
				<GoogleMap
					bootstrapURLKeys={{key: 'AIzaSyBcjFzmEx9Y8-ScukvP8wdlTBeefpiYSxg'}}
					zoom={14}
					defaultCenter={{lat: Number(initialCenter.lat), lng: Number(initialCenter.lng)}}
					center={{lat: Number(lat), lng: Number(lng)}}
					onChildClick={this.onChildClick}
					yesIWantToUseGoogleMapApiInternals
					onGoogleApiLoaded={this.onGoogleApiLoaded}
				>
			        <Marker
			            lat={Number(lat)}
			            lng={Number(lng)}
			        />
			    </GoogleMap>
		    </div>
		)
	}
}

export default LocationInputMap