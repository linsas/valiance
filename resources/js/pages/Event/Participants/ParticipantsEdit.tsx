import React from 'react'
import { Fab, Tooltip } from '@mui/material'
import TeamIcon from '@mui/icons-material/Group'

import AppContext from '../../../main/AppContext'
import useFetch from '../../../utility/useFetch'
import { IEvent, IFormParticipant } from '../EventTypes'
import ParticipantsForm from './ParticipantsForm'

function ParticipantsEdit({ event, update } : {
	event: IEvent
	update: () => void
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/tournaments/' + event.id + '/teams', 'PUT')

	const onSubmit = (list: Array<IFormParticipant>) => {
		fetchEdit({ participants: list.map(p => p.team.id) }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Tooltip title='Edit participants' placement='left' arrow>
			<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, left: 'calc(100vw - 100px)' }}>
				<TeamIcon />
			</Fab>
		</Tooltip>
		<ParticipantsForm open={formOpen} list={event.participants} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default ParticipantsEdit
