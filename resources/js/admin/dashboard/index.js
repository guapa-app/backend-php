import React, { Component } from 'react'
import { connect } from 'react-redux'

import MuiCard from '@material-ui/core/Card'
import CardContent from '@material-ui/core/CardContent'
import GridContainer from '../components/Grid/GridContainer'
import GridItem from '../components/Grid/GridItem'
import { withStyles } from '@material-ui/core/styles'
import Paper from '@material-ui/core/Paper'
import DoneIcon from '@material-ui/icons/Done'
import MoneyOffIcon from '@material-ui/icons/MoneyOff'

import {
	Title, ViewTitle, showNotification, withDataProvider
} from 'react-admin'

import Icon from "@material-ui/core/Icon"
import Warning from "@material-ui/icons/Warning"
import Store from "@material-ui/icons/Store"
import DateRange from "@material-ui/icons/DateRange"
import AttachMoney from "@material-ui/icons/AttachMoney"
import LocalOffer from "@material-ui/icons/LocalOffer"
import Update from "@material-ui/icons/Update"
import ArrowUpward from "@material-ui/icons/ArrowUpward"
import AccessTime from "@material-ui/icons/AccessTime"
import Accessibility from "@material-ui/icons/Accessibility"
import BugReport from "@material-ui/icons/BugReport"
import Code from "@material-ui/icons/Code"
import Cloud from "@material-ui/icons/Cloud"

import Card from "../components/Card/Card.js"
import CardHeader from "../components/Card/CardHeader.js"
import CardIcon from "../components/Card/CardIcon.js"
import CardBody from "../components/Card/CardBody.js"
import CardFooter from "../components/Card/CardFooter.js"

import Danger from "../components/Typography/Danger.js"

// import ChartistGraph from "react-chartist"

import _ from 'lodash'
import { myNumber } from '../utils/common'

import styles from './styles.js'

class Dashboard extends Component {
	constructor(props) {
		super(props)
		this.state = {
			loadingTotals: true,
			mainStats: {},
			loadingFinanceStats: true,
			financeStats: {},
			chartData: {
				
			},
			loadingChartData: true,
		}
	}

	componentDidMount() {
		// this.getMainStats()
		// this.getChartData()
	}

	getMainStats() {
		const { dataProvider, dispatch } = this.props
		dataProvider.getOne('dashboard/main_stats', { id: 0 }).then(res => {
			this.setState({ mainStats: res.data, loadingTotals: false })
		}).catch(err => {
			dispatch(showNotification('An error occurred while fetching main statistics', 'warning'))
			this.setState({ loadingTotals: false })
		})
	}

	getFinanceStats() {
		const { dataProvider, dispatch } = this.props
		dataProvider.getOne('dashboard/finance_stats', { id: 0 }).then(res => {
			this.setState({ financeStats: res.data, loadingFinanceStats: false })
		}).catch(err => {
			dispatch(showNotification('An error occurred while fetching main statistics', 'warning'))
			this.setState({ loadingFinanceStats: false })
		})
	}

	getChartData() {
		const { dataProvider, dispatch } = this.props
		dataProvider.getOne('dashboard/chart_data', { id: 0 }).then(res => {
			this.setState({ chartData: res.data, loadingChartData: false })
		}).catch(err => {
			dispatch(showNotification('An error occurred while fetching chart data', 'warning'))
			this.setState({ loadingChartData: false })
		})
	}

	render() {
		const { classes, resources } = this.props
		const {
			mainStats, loadingChartData, loadingTotals,
			chartData,
		} = this.state

		return (
			<MuiCard className={classes.mainCard}>
				<Title title="Dashboard" />
				<CardContent>
					<div className={classes.root}>
						<GridContainer>
        					<GridItem xs={12} sm={6} md={3}>
					          <Card>
					            <CardHeader color="info" stats icon>
					              <CardIcon color="info">
					                <Icon>supervisor_account</Icon>
					              </CardIcon>
					              <p className={classes.cardCategory}>Users</p>
					              <h3 className={classes.cardTitle}>
					                { mainStats && myNumber(mainStats.totalUsersThisMonth).toLocaleString() }
					              </h3>
					            </CardHeader>
					            <CardFooter stats>
					              <div className={classes.stats}>
					                <DateRange />
					                <span className={classes.thisMonth}>This month</span>
					                <small className={classes.total}>
					                	{ mainStats && myNumber(mainStats.totalUsers).toLocaleString() } Total
					                </small>
					              </div>
					            </CardFooter>
					          </Card>
					        </GridItem>
					        {/*<GridItem xs={12} sm={6} md={3}>
					          <Card>
					            <CardHeader color="success" stats icon>
					              <CardIcon color="success">
					                <Icon>assignment_ind</Icon>
					              </CardIcon>
					              <p className={classes.cardCategory}>Organizers</p>
					              <h3 className={classes.cardTitle}>
					              	{ mainStats && myNumber(mainStats.totalOrganizersThisMonth).toLocaleString() }
					              </h3>
					            </CardHeader>
					            <CardFooter stats>
					              <div className={classes.stats}>
					                <DateRange />
					                <span className={classes.thisMonth}>This month</span>
					                <small className={classes.total}>
					                	{ mainStats && myNumber(mainStats.totalOrganizers).toLocaleString() } Total
					                </small>
					              </div>
					            </CardFooter>
					          </Card>
					        </GridItem>
					        <GridItem xs={12} sm={6} md={3}>
					          <Card>
					            <CardHeader color="danger" stats icon>
					              <CardIcon color="danger">
					                <Icon>event</Icon>
					              </CardIcon>
					              <p className={classes.cardCategory}>Events</p>
					              <h3 className={classes.cardTitle}>
					              	{ mainStats && myNumber(mainStats.totalEventsThisMonth).toLocaleString() }
					              </h3>
					            </CardHeader>
					            <CardFooter stats>
					              <div className={classes.stats}>
					                <DateRange />
					                <span className={classes.thisMonth}>This month</span>
					                <small className={classes.total}>
					                	{ mainStats && myNumber(mainStats.totalEvents).toLocaleString() } Total
					                </small>
					              </div>
					            </CardFooter>
					          </Card>
					        </GridItem>
					        <GridItem xs={12} sm={6} md={3}>
					          <Card>
					            <CardHeader color="warning" stats icon>
					              <CardIcon color="warning">
					                <Icon>monetization_on</Icon>
					              </CardIcon>
					              <p className={classes.cardCategory}>Sold Tickets</p>
					              <h3 className={classes.cardTitle}>
					              	{ mainStats && myNumber(mainStats.totalTickets).toLocaleString() }
					              </h3>
					            </CardHeader>
					            <CardFooter stats>
					              <div className={classes.stats}>
					                <AttachMoney />
					                <span className={classes.thisMonth}>Total Commissions</span>
					                <small className={classes.total}>
					                	{ mainStats && myNumber(mainStats.totalRevenue).toLocaleString() } SAR
					                </small>
					              </div>
					            </CardFooter>
					          </Card>
					        </GridItem>*/}
        				</GridContainer>
					</div>
				</CardContent>
			</MuiCard>
		)
	}
}

export default withDataProvider(connect(state => {
	return {
		resources: state.admin.resources,
	}
})(withStyles(styles)(Dashboard)))
