import React from 'react'
import ReactDOM from 'react-dom'
import { BrowserRouter, Switch, Route } from 'react-router-dom'
import { Container } from '@material-ui/core'

import Welcome from './Welcome'

function App() {
	return <Container>
		<BrowserRouter>
			<Switch>
				<Route component={Welcome} />
			</Switch>
		</BrowserRouter>
	</Container>
}

if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'))
}
