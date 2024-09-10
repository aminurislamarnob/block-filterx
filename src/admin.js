import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import {
	HashRouter as Router,
	Routes,
	Route
} from 'react-router-dom';
import './styles/styles.css';
import './styles/index.scss'; // Include your custom Sass styles
import Layout from './Components/Layout';
import GlobalSettings from './Components/GlobalSettings';
import UserRoleSpecificSettings from './Components/UserRoleSpecificSettings';
import UserSpecificSettings from './Components/UserSpecificSettings';

const App = () => (
	<Router>
		<Routes>
			<Route path="/" element={ <Layout /> }>
                <Route index element={ <GlobalSettings /> } />
                <Route path="user-role-specific-settings" element={ <UserRoleSpecificSettings /> } />
                <Route path="user-specific-settings" element={ <UserSpecificSettings /> } />
                {/* Add more routes here */}
            </Route>
		</Routes>
	</Router>
);

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById(
		'block-filterx-settings'
	);
	const root = createRoot( container );
	root.render( <App /> );
} );
