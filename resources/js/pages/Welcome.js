import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { makeStyles } from '@material-ui/core'
import { Box, Typography, Grid, Paper, ButtonBase } from '@material-ui/core'

import navigation from '../utility/navigation'

const useStyles = makeStyles(theme => ({
	gridLink: {
		display: 'flex',
		alignItems: 'center',
	},
	button: {
		height: 100,
		width: '100%',
		color: theme.palette.primary.main,
		'&:hover': {
			textDecoration: 'underline',
		},
	}
}))

function GridLink({ title, to, icon: Icon }) {
	const classes = useStyles()
	return <Grid item xs={12} sm={6} md={3}>
		<Paper>
			<ButtonBase component={RouterLink} to={to} focusRipple className={classes.button}>
				<Typography variant='h6' className={classes.gridLink}>
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
