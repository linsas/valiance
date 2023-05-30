import React from 'react'
import { Fab, Tooltip } from '@mui/material'
import PlayIcon from '@mui/icons-material/PlayArrow'

import AppContext from '../../../main/AppContext'
import useFetch from '../../../utility/useFetch'
import { IEvent } from '../EventTypes'

function ProgressionEdit({ event, update }:{
	event: IEvent
	update: () => void
}) {
	const context = React.useContext(AppContext)

	const [isAdvancing, fetchAdvance] = useFetch('/tournaments/' + event.id + '/advance', 'POST')

	const pressAdvance = () => {
		fetchAdvance().then(() => update(), context.handleFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Tooltip title='Advance to the next round' placement='left' arrow>
			<Fab color='primary' onClick={pressAdvance} style={{ position: 'fixed', bottom: 88, left: 'calc(100vw - 100px)' }}>
				<PlayIcon />
			</Fab>
		</Tooltip>
	</>
}

export default ProgressionEdit
