import React, { Component } from 'react'
import PropTypes from 'prop-types'
import GoogleMap, { SimpleMarker } from 'google-map-react'
import { siteUrl } from '../utils/common'
import Marker from './Marker'

class Circle extends Component {
	constructor(props) {
		super(props)
	}

	componentDidMount() {
		this.renderCircle()
	}

	componentWillUnmount() {

	}

	componentDidUpdate(prevProps) {
		if (this.props.map !== prevProps.map) {
          if (this.circle) {
            this.circle.setMap(null);
          }
          this.renderCircle();
        }
	}

	renderCircle() {
		const { map, mapCenter, radius } = this.props
		if (! (map && mapCenter && radius)) {
			return null
		}
		const circleProps = Object.assign({}, this.props)
		circleProps.center = {
			lat: Number(mapCenter.lat),
			lng: Number(mapCenter.lng),
		}

		circleProps.radius = Number(radius) * 1000

		this.circle = new this.props.google.maps.Circle({
			...circleProps
		})
	}

	render() {
		return null
	}
}

const AnyReactComponent = ({ text }) => <div>{text}</div>

class MapField extends Component {
	constructor(props) {
		super(props)
		this.state = {
			lat: props.record.lat,
			lng: props.record.lng,
		}
	}

	render() {
		const { record: { lat, lng }, className } = this.props

		if ( ! lat && ! lng) { return null }
		return (
			<div className={className}>
				<GoogleMap
					bootstrapURLKeys={{key: 'AIzaSyBcjFzmEx9Y8-ScukvP8wdlTBeefpiYSxg'}}
					zoom={15}
					defaultCenter={{lat: Number(lat), lng: Number(lng)}}
					center={{lat: Number(lat), lng: Number(lng)}}
					style={{
						width: '100%',
						height: 500,
					}}
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


MapField.defaultProps = {
	addLabel: true,
}

MapField.propTypes = {
	record: PropTypes.object,

}

export default MapField

{/*icon={{
		        		url: siteUrl('storage/icons/pharmacy.png'),
		        		//anchor: new this.props.google.maps.Point(userLocation.lat, userLocation.lng),
		        		scaledSize: new this.props.google.maps.Size(32, 32),
		        	}}*/}