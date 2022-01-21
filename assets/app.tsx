import React from 'react'
import ReactDOM from 'react-dom'
import { BrowserRouter as Router } from 'react-router-dom'
import Base from './Base'
import './fonts/Inter/font.css'
import { AppProvider } from './provider/AppProvider'
import './translations/i18n'

ReactDOM.render(
	<Router>
		<AppProvider>
			<Base />
		</AppProvider>
	</Router>,
	document.getElementById('app')
)
