import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { AppBar, Hidden, IconButton, Link, SvgIcon, Toolbar, Tooltip, Typography } from '@mui/material'
import HomeIcon from '@mui/icons-material/Home'
import FilledLightbulbIcon from '@mui/icons-material/EmojiObjects'
import HollowLightbulbIcon from '@mui/icons-material/EmojiObjectsOutlined'
import AccountCircleOutlinedIcon from '@mui/icons-material/AccountCircleOutlined'
import ExitIcon from '@mui/icons-material/MeetingRoom';

import AppContext from './AppContext'
import navigation from '../utility/navigation'

function HeaderNavLink({ title, to, icon: Icon }:{
	title: string,
	to: string,
	icon: typeof SvgIcon,
}) {
	return <>
		<Hidden smDown>
			<Link color='inherit' component={RouterLink} to={to} underline='hover' style={{ display: 'inline-flex', margin: 4 }}>
				<Icon />
				<Typography component='span'>{title}</Typography>
			</Link>
		</Hidden>

		<Hidden smUp>
			<Tooltip title={title} arrow>
				<IconButton color='inherit' component={RouterLink} to={to}>
					<Icon />
				</IconButton>
			</Tooltip>
		</Hidden>
	</>
}

function Header({ isDark, setDark, onPressLogin, onPressLogout, }:{
	isDark: boolean,
	setDark: (darkMode: boolean) => void,
	onPressLogin: () => void,
	onPressLogout: () => void,
}) {
	const context = React.useContext(AppContext)

	return <AppBar position='sticky' style={{ marginBottom: 32 }}>
		<Toolbar>

			<Hidden smDown>
				<Typography variant='h6'>
					<Link color='inherit' component={RouterLink} to='/' underline='hover'>Valiance</Link>
				</Typography>
			</Hidden>
			<Hidden smUp>
				<Tooltip title='Home' arrow>
					<IconButton color='inherit' component={RouterLink} to={'/'} edge='start'>
						<HomeIcon />
					</IconButton>
				</Tooltip>
			</Hidden>

			<nav style={{ flexGrow: 1, textAlign: 'center' }}>
				{navigation.map((item) =>
					<HeaderNavLink key={item.to} title={item.title} to={item.to} icon={item.icon} />
				)}
			</nav>

			<Tooltip title='Toggle dark mode' arrow>
				<IconButton color='inherit' onClick={() => setDark(!isDark)}>
					{isDark ? <HollowLightbulbIcon /> : <FilledLightbulbIcon />}
				</IconButton>
			</Tooltip>

			{context.jwt == null ? (
				<Tooltip title='Login' arrow>
					<IconButton color='inherit' edge='end' onClick={onPressLogin}>
						<AccountCircleOutlinedIcon />
					</IconButton>
				</Tooltip>
			) : (
				<Tooltip title='Logout' arrow>
					<IconButton color='inherit' edge='end' onClick={onPressLogout}>
						<ExitIcon />
					</IconButton>
				</Tooltip>
			)}

		</Toolbar>
	</AppBar>
}

export default Header
