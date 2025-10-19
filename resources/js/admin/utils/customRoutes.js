import React from 'react'
import { Route } from 'react-router-dom'
// import ImportMedicines from '../medicines/import'
// import AddStaff from '../providers/addStaff'
// import AdvancedOrdersView from '../orders/advancedView'
import { SettingCreate, SettingEdit } from '../dashboard/settings'

export default [
	<Route exact path="/settings" component={SettingEdit} />
]
