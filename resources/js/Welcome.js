import React from 'react'
import { Box, Typography, Paper } from '@material-ui/core'

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
	</>
}

export default Welcome
