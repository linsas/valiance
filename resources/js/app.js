import React from 'react'
import ReactDOM from 'react-dom'
import { BrowserRouter, Switch, Route } from 'react-router-dom'
import { Box, Container, CssBaseline } from '@material-ui/core'
import { createTheme } from '@material-ui/core/styles'
import ThemeProvider from '@material-ui/styles/ThemeProvider'

import AppContext from './main/AppContext'
import Header from './main/Header'
import LoginControl from './main/LoginControl'

import Home from './pages/Welcome'
import NotFound from './pages/NotFound'
import Player from './pages/Player/Player'
import PlayerList from './pages/Player/PlayerList'
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
	const [jwt, setJWT] = React.useState(null)

	const [isLoginFormOpen, setLoginFormOpen] = React.useState(false)
	const onPressLogout = () => {
		setJWT(null)
	}

	return <>
		{/* providers go here... */}
		<ThemeProvider theme={isDarkTheme ? darkTheme : lightTheme}>
			<CssBaseline />
			<AppContext.Provider value={{ jwt, setJWT }}>

				<BrowserRouter>
					<ThemeProvider theme={darkTheme}>
						<Header isDark={isDarkTheme} setDark={setDarkTheme} onPressLogin={() => setLoginFormOpen(true)} onPressLogout={onPressLogout} />
					</ThemeProvider>

					<Container maxWidth='md'>
						<Switch>
							<Route exact path='/Players/:id' component={Player} />
							<Route exact path='/Players' component={PlayerList} />

							<Route exact path='/Teams/:id' component={Team} />
							<Route exact path='/Teams' component={TeamList} />

							<Route exact path='/' component={Home} />
							<Route component={NotFound} />
						</Switch>
					</Container>
				</BrowserRouter>

				<Box p={4} /> {/* footer */}

				<LoginControl isOpen={isLoginFormOpen} setOpen={setLoginFormOpen} />

			</AppContext.Provider>
		</ThemeProvider>
	</>
}

if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'))
}
