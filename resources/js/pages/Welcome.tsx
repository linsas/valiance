import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { Box, Typography, Grid, Paper, ButtonBase, SvgIcon } from '@mui/material'

import navigation from '../utility/navigation'

function GridLink({ title, to, icon: Icon }: {
	title: string
	to: string
	icon: typeof SvgIcon
}) {
	return <Grid item xs={12} sm={6} md={3}>
		<Paper>
			<ButtonBase component={RouterLink} to={to} focusRipple sx={{ height: '100px', width: 1, color: 'primary.main', '&:hover': { textDecoration: 'underline', }, }}>
				<Typography variant='h6' sx={{ display: 'flex', alignItems: 'center' }}>
					<Icon fontSize='medium' />
					{title}
				</Typography>
			</ButtonBase>
		</Paper>
	</Grid>
}

function Welcome() {
	return <>
		<Paper><Box p={2}>
			<Typography variant='h5'>
				Welcome to Valiance, the tournament organisation system
			</Typography>
			<Box my={2} />
			<Typography>
				Here you can view teams, players and tournaments in the system and track their progress.
			</Typography>
			<Typography>
				If you have access, you can create and remove teams, players, or events.
			</Typography>
		</Box></Paper>

		<Box my={2} />

		<Grid container direction='row' alignItems='center' spacing={2}>
			{navigation.map(n =>
				<GridLink key={n.to} title={n.title} to={n.to} icon={n.icon} />
			)}
		</Grid>
	</>
}

export default Welcome
