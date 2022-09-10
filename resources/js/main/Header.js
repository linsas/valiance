import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { AppBar, Hidden, IconButton, Link, Toolbar, Tooltip, Typography } from '@material-ui/core'
import HomeIcon from '@material-ui/icons/Home'
import EmojiObjectsIcon from '@material-ui/icons/EmojiObjects'
import EmojiObjectsOutlinedIcon from '@material-ui/icons/EmojiObjectsOutlined'
import AccountCircle from '@material-ui/icons/AccountCircle'
import AccountCircleOutlinedIcon from '@material-ui/icons/AccountCircleOutlined'

import AppContext from '../main/AppContext'
import navigation from '../utility/navigation'

function HeaderNavLink({ title, to, icon: Icon }) {
	return <>
		<Hidden xsDown>
			<Link color='inherit' component={RouterLink} to={to} style={{ display: 'inline-flex', margin: 4 }}>
				<Icon />
				<Typography component='span'>{title}</Typography>
			</Link>
		</Hidden>

		<Hidden smUp>
			<Tooltip title={title} arrow>
				<IconButton component={RouterLink} to={to}>
					<Icon />
				</IconButton>
			</Tooltip>
		</Hidden>
	</>
}

function Header({ isDark, setDark, onPressLogin, onPressLogout, }) {
	const context = React.useContext(AppContext)

	return <AppBar position='sticky' style={{ marginBottom: 32 }}>
		<Toolbar>

			<Hidden xsDown>
				<Typography variant='h6'>
					<Link color='inherit' component={RouterLink} to='/'>Valiance</Link>
				</Typography>
			</Hidden>
			<Hidden smUp>
				<Tooltip title='Home' arrow>
					<IconButton component={RouterLink} to={'/'} edge='start'>
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
				<IconButton onClick={() => setDark(!isDark)}>
					{isDark ? <EmojiObjectsOutlinedIcon /> : <EmojiObjectsIcon />}
				</IconButton>
			</Tooltip>

			{context.jwt == null ? (
				<Tooltip title='Login' arrow>
					<IconButton edge='end' onClick={onPressLogin}>
						<AccountCircleOutlinedIcon />
					</IconButton>
				</Tooltip>
			) : (
				<Tooltip title='Logout' arrow>
					<IconButton edge='end' onClick={onPressLogout}>
						<AccountCircle />
					</IconButton>
				</Tooltip>
			)}

		</Toolbar>
	</AppBar>
}

export default Header
