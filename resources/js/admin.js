import React from 'react'
import {render} from 'react-dom'
import {Admin, Resource} from 'react-admin'
import authClient from './admin/clients/authClient'
import userReducer from './admin/redux/reducers/user'
import restClient from './admin/clients/restClient'

import Layout from './admin/components/Layout'
import Dashboard from './admin/dashboard'
import {AdminCreate, AdminEdit, AdminIcon, AdminList} from './admin/users/admins'
import {UserCreate, UserEdit, UserIcon, UserList} from './admin/users'
import {VendorCreate, VendorEdit, VendorIcon, VendorList, VendorShow} from './admin/vendors'
import {ProductCreate, ProductEdit, ProductIcon, ProductList, ProductShow} from './admin/products'
import {OfferCreate, OfferEdit, OfferIcon, OfferList, OfferShow} from './admin/offers'
import {TaxonomyCreate, TaxonomyEdit, TaxonomyIcon, TaxonomyList} from './admin/taxonomies'
import {NotificationCreate, NotificationIcon, NotificationList} from './admin/notifications'
import {AddressCreate, AddressEdit, AddressIcon, AddressList} from './admin/addresses'
import {PostCreate, PostEdit, PostIcon, PostList, PostShow} from './admin/posts'
import {CommentCreate, CommentEdit, CommentIcon, CommentList} from './admin/posts/comments'
import {HistoryCreate, HistoryEdit, HistoryIcon, HistoryList} from './admin/history'
import {ReviewCreate, ReviewEdit, ReviewIcon, ReviewList} from './admin/reviews'
import {PageCreate, PageEdit, PageIcon, PageList} from './admin/pages'
import {CityCreate, CityEdit, CityIcon, CityList} from './admin/places/cities'
import {MessageIcon, MessageList, MessageShow} from './admin/messages'
import {OrderEdit, OrderIcon, OrderList, OrderShow} from './admin/orders'
import {SettingCreate, SettingIcon} from './admin/dashboard/settings'

import CustomRoutes from './admin/utils/customRoutes'

const history = require('history').createBrowserHistory({
    'basename': 'admin/'
})

const customReducers = {
    user: userReducer,
}

render (
  <Admin title="Admin Dashboard" layout={Layout} dashboard={Dashboard} authProvider={authClient} dataProvider={restClient} history={history} customRoutes={CustomRoutes} customReducers={customReducers}>
      <Resource name="notifications" options={{label: "Notifications"}} list={NotificationList} create={NotificationCreate} icon={NotificationIcon}/>
{/*
    <Resource name="admins" options={{label: "Admins"}} list={AdminList} create={AdminCreate} edit={AdminEdit} icon={AdminIcon} />
    <Resource name="users" options={{label: "Users"}} list={UserList} create={UserCreate} edit={UserEdit} icon={UserIcon} />
    <Resource name="vendors" options={{label: "Vendors"}} list={VendorList} create={VendorCreate} edit={VendorEdit} show={VendorShow} icon={VendorIcon} />
    <Resource name="products" options={{label: "Products"}} list={ProductList} create={ProductCreate} edit={ProductEdit} show={ProductShow} icon={ProductIcon} />
    <Resource name="offers" options={{label: "Offers"}} list={OfferList} create={OfferCreate} edit={OfferEdit} show={OfferShow} icon={OfferIcon} />
    <Resource name="taxonomies" options={{label: "Taxonomies"}} list={TaxonomyList} create={TaxonomyCreate} edit={TaxonomyEdit} icon={TaxonomyIcon} />
    <Resource name="addresses" options={{label: "Addresses"}} list={AddressList} create={AddressCreate} edit={AddressEdit} icon={AddressIcon} />
    <Resource name="posts" options={{label: "Posts"}} list={PostList} create={PostCreate} edit={PostEdit} show={PostShow} icon={PostIcon} />
    <Resource name="comments" options={{label: "Comments"}} list={CommentList} create={CommentCreate} edit={CommentEdit} icon={CommentIcon} />
    <Resource name="history" options={{label: "Medical history"}} list={HistoryList} create={HistoryCreate} edit={HistoryEdit} icon={HistoryIcon} />
    <Resource name="reviews" options={{label: "Reviews"}} list={ReviewList} create={ReviewCreate} edit={ReviewEdit} icon={ReviewIcon} />
    <Resource name="pages" options={{label: "Pages"}} list={PageList} create={PageCreate} edit={PageEdit} icon={PageIcon} />
    <Resource name="cities" options={{label: "Cities"}} list={CityList} create={CityCreate} edit={CityEdit} icon={CityIcon} />
    <Resource name="messages" options={{label: "Messages"}} list={MessageList} show={MessageShow} icon={MessageIcon} />
    <Resource name="orders" options={{label: "Orders"}} list={OrderList} edit={OrderEdit} show={OrderShow} icon={OrderIcon} />
    <Resource name="settings" options={{label: "Settings"}} create={SettingCreate} icon={SettingIcon} />
*/}
  </Admin>, document.getElementById("admin")
)
