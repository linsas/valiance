import React from 'react'
import ReactDOM from 'react-dom'
import { BrowserRouter, Switch as RouterSwitch, Route } from 'react-router-dom'
import { Container, CssBaseline } from '@mui/material'
import { createTheme } from '@mui/material/styles'
import { ThemeProvider } from '@mui/material/styles'

import AppContext, { JWT } from './main/AppContext'
import Header from './main/Header'
import Footer from './main/Footer'
import LoginControl from './main/LoginControl'
import NotificationBar from './main/NotificationBar'
import errorAsText from './utility/errorAsText'

import Home from './pages/Welcome'
import NotFound from './pages/NotFound'
import Player from './pages/Player/Player'
import PlayerList from './pages/Player/PlayerList'
import Team from './pages/Team/Team'
import TeamList from './pages/Team/TeamList'
import Event from './pages/Event/Event'
import EventList from './pages/Event/EventList'
import Matchup from './pages/Matchup/Matchup'
import MatchupList from './pages/Matchup/MatchupList'

const mainPalette = {
	primary: { main: '#F7484E', },
	// secondary: { main: '#1976D2', },
	error: { main: '#DB0092', },
}

const lightTheme = createTheme({ palette: { ...mainPalette, mode: 'light', }, })
const darkTheme = createTheme({ palette: { ...mainPalette, mode: 'dark', }, })

function App() {
	const [isDarkTheme, setDarkTheme] = React.useState<boolean>(false)
	const [jwt, setJWT] = React.useState<JWT | null>(null)
	const [notificationQueue, setNotificationQueue] = React.useState<Array<string>>([])

	const notifyFetchError = (error) => {
		setNotificationQueue(q => q.concat(errorAsText(error)))
	}

	const [isLoginFormOpen, setLoginFormOpen] = React.useState(false)
	const onPressLogout = () => {
		setJWT(null)
	}

	return <>
		{/* providers go here... */}
		<ThemeProvider theme={isDarkTheme ? darkTheme : lightTheme}>
			<CssBaseline />
			<AppContext.Provider value={{ jwt, setJWT, notifyFetchError }}>
				<BrowserRouter>

					<div style={{ minHeight: '100vh', display: 'flex', flexDirection: 'column' }}>

						{/* <ThemeProvider theme={lightTheme}> */}
							<Header isDark={isDarkTheme} setDark={setDarkTheme} onPressLogin={() => setLoginFormOpen(true)} onPressLogout={onPressLogout} />
						{/* </ThemeProvider> */}

						<Container style={{ flex: '1 0 auto' }} maxWidth='md'>
							<RouterSwitch>
								<Route exact path='/Players/:id' component={Player} />
								<Route exact path='/Players' component={PlayerList} />

								<Route exact path='/Teams/:id' component={Team} />
								<Route exact path='/Teams' component={TeamList} />

								<Route exact path='/Events/:id' component={Event} />
								<Route exact path='/Events' component={EventList} />

								<Route exact path='/Matchups/:id' component={Matchup} />
								<Route exact path='/Matchups' component={MatchupList} />

								<Route exact path='/' component={Home} />
								<Route component={NotFound} />
							</RouterSwitch>
						</Container>

						<Footer />

					</div>

					<NotificationBar queue={notificationQueue} setQueue={setNotificationQueue} />
					<LoginControl isOpen={isLoginFormOpen} setOpen={setLoginFormOpen} />

				</BrowserRouter>
			</AppContext.Provider>
		</ThemeProvider>
	</>
}

if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'))
}
