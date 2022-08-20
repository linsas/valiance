import React from 'react'
import { Fab, Tooltip } from '@material-ui/core'
import PlayIcon from '@material-ui/icons/PlayArrow'

import AppContext from '../../../main/AppContext'
import useFetch from '../../../utility/useFetch'

function ProgressionEdit({ event, update }) {
	const context = React.useContext(AppContext)

	const [isAdvancing, fetchAdvance] = useFetch('/api/tournaments/' + event.id + '/advance', 'POST')

	const pressAdvance = () => {
		fetchAdvance().then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Tooltip title='Advance to the next round' arrow>
			<Fab color='primary' onClick={pressAdvance} style={{ position: 'fixed', bottom: 88, left: 'calc(100vw - 100px)' }}>
				<PlayIcon />
			</Fab>
		</Tooltip>
	</>
}

export default ProgressionEdit
