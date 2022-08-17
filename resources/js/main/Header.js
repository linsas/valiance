import React from 'react'
import { AppBar, IconButton, Toolbar, Tooltip, Typography } from '@material-ui/core'
import EmojiObjectsIcon from '@material-ui/icons/EmojiObjects'
import EmojiObjectsOutlinedIcon from '@material-ui/icons/EmojiObjectsOutlined'

function Header({ isDark, setDark }) {
	return <AppBar position='sticky' style={{ marginBottom: 45 }}>
		<Toolbar>

			<Typography variant='h6' style={{ flexGrow: 1 }}>Valiance</Typography>

			<Tooltip title='Toggle dark mode' arrow>
				<IconButton edge='end' onClick={() => setDark(!isDark)}>
					{isDark ? <EmojiObjectsOutlinedIcon /> : <EmojiObjectsIcon />}
				</IconButton>
			</Tooltip>

		</Toolbar>
	</AppBar>
}

export default Header
