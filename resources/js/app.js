import React from 'react'
import ReactDOM from 'react-dom'
import { BrowserRouter, Switch, Route } from 'react-router-dom'
import { Box, Container, CssBaseline } from '@material-ui/core'
import { createTheme } from '@material-ui/core/styles'
import ThemeProvider from '@material-ui/styles/ThemeProvider'

import Header from './main/Header'

import Home from './pages/Welcome'
import NotFound from './pages/NotFound'
import Team from './pages/Team/Team'
import TeamList from './pages/Team/TeamList'

const mainPalette = {
	primary: { main: '#F7484E', },
	// secondary: { main: '#1976D2', },
	error: { main: '#DB0092', },
}

const lightTheme = createTheme({ palette: { ...mainPalette, type: 'light', }, })
const darkTheme = createTheme({ palette: { ...mainPalette, type: 'dark', }, })

function App() {
	const [isDarkTheme, setDarkTheme] = React.useState(true)

	return <>
		{/* providers go here... */}
		<ThemeProvider theme={isDarkTheme ? darkTheme : lightTheme}>
			<CssBaseline />

			<BrowserRouter>
				<ThemeProvider theme={darkTheme}>
					<Header isDark={isDarkTheme} setDark={setDarkTheme} />
				</ThemeProvider>

				<Container maxWidth='md'>
					<Switch>
						<Route exact path='/Teams/:id' component={Team} />
						<Route exact path='/Teams' component={TeamList} />

						<Route exact path='/' component={Home} />
						<Route component={NotFound} />
					</Switch>
				</Container>
			</BrowserRouter>

			<Box p={4} /> {/* footer */}
		</ThemeProvider>
	</>
}

if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'))
}
