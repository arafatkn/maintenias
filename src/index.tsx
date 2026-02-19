/**x
 * External dependencies
 */
import { createRoot } from '@wordpress/element';
import React from 'react';

/**
 * Internal dependencies
 */
import App from './App';

// Import the stylesheet for the plugin.
import './assets/styles/app.css';

// Render the App component into the DOM
const appElement = document.getElementById('maintenias');

if (appElement) {
  createRoot(appElement).render(<App />);
}
