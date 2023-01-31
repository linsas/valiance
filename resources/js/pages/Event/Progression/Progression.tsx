import React from 'react'
import { Box, Typography, Paper } from '@mui/material'

import { IEvent } from '../EventTypes'
import ProgressionEdit from './ProgressionEdit'
import ProgressionStructure from './ProgressionStructure'

function Progression({ event, update }:{
	event: IEvent
	update: () => void
}) {
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
		<ProgressionStructure event={event} />
	</>
}

export default Progression
