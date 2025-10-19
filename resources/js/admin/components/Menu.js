/**
 * Using a custom menu to be able to add menu items without lists
 * 
 */

import React from 'react'
import { connect } from 'react-redux'
import { MenuItemLink, getResources, Responsive, DashboardMenuItem } from 'react-admin'
import { withRouter } from 'react-router-dom'
import { SettingIcon } from '../dashboard/settings'

const MyMenu = ({ resources, onMenuClick, logout, hasDashboard }) => (
    <div>
        {hasDashboard &&
            <DashboardMenuItem onClick={onMenuClick} />
        }
        {resources.map(resource => (
            <MenuItemLink
            	key={resource.name}
            	to={`/${resource.name}`}
            	primaryText={resource.options.label}
            	onClick={onMenuClick}
            	leftIcon={ resource.icon ? <resource.icon /> : null }
            />
        ))}
        <Responsive
            small={logout}
            medium={null} // Pass null to render nothing on larger devices
        />
    </div>
)

const mapStateToProps = state => ({
    resources: getResources(state),
})

export default withRouter(connect(mapStateToProps)(MyMenu))