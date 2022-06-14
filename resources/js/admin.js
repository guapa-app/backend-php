import React from 'react'
import { render } from 'react-dom'
import { Admin, Resource } from 'react-admin'
import authClient from './admin/clients/authClient'
import userReducer from './admin/redux/reducers/user'
import { httpClient } from './admin/clients/httpClient'
import restClient from './admin/clients/restClient'

import Layout from './admin/components/Layout'
import Dashboard from './admin/dashboard'
import { AdminList, AdminCreate, AdminEdit, AdminIcon } from './admin/users/admins'
import { UserList, UserCreate, UserEdit, UserIcon } from './admin/users'
import { VendorList, VendorCreate, VendorEdit, VendorShow, VendorIcon } from './admin/vendors'
import { ProductList, ProductCreate, ProductEdit, ProductShow, ProductIcon } from './admin/products'
import { OfferList, OfferCreate, OfferEdit, OfferShow, OfferIcon } from './admin/offers'
import { TaxonomyList, TaxonomyCreate, TaxonomyEdit, TaxonomyIcon } from './admin/taxonomies'
import { AddressList, AddressCreate, AddressEdit, AddressIcon } from './admin/addresses'
import { PostList, PostCreate, PostEdit, PostShow, PostIcon } from './admin/posts'
import { CommentList, CommentCreate, CommentEdit, CommentIcon } from './admin/posts/comments'
import { HistoryList, HistoryCreate, HistoryEdit, HistoryIcon } from './admin/history'
import { ReviewList, ReviewCreate, ReviewEdit, ReviewIcon } from './admin/reviews'
import { PageList, PageCreate, PageEdit, PageIcon } from './admin/pages'
import { CityList, CityCreate, CityEdit, CityIcon } from './admin/places/cities'
import { MessageList, MessageShow, MessageIcon } from './admin/messages'
import{ OrderList, OrderEdit, OrderShow, OrderIcon } from './admin/orders'
import { SettingCreate, SettingIcon } from './admin/dashboard/settings'

import CustomRoutes from './admin/utils/customRoutes'

const history = require('history').createBrowserHistory({
  'basename': 'admin/'
})

const customReducers = {
  user: userReducer,
}

render (
  <Admin title="Admin Dashboard" layout={Layout} dashboard={Dashboard} authProvider={authClient} dataProvider={restClient} history={history} customRoutes={CustomRoutes} customReducers={customReducers}>
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
  </Admin>, document.getElementById("admin")
)
