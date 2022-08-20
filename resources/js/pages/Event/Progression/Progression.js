import React from 'react'
import { Box, Typography, Paper } from '@material-ui/core'

import ProgressionEdit from './ProgressionEdit'
import ProgressionMatchups from './ProgressionMatchups'

function Progression({ event, update }) {
	if (event.matchups.length === 0)
		return <>
			<ProgressionEdit event={event} update={update} />
			<Paper>
				<Box my={2} p={2}>
					<Typography align='center' color='textSecondary'>This event has not progressed yet.</Typography>
				</Box>
			</Paper>
		</>

	return <>
		<ProgressionEdit event={event} update={update} />
		<ProgressionMatchups event={event} />
	</>
}

export default Progression
