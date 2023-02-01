import React from 'react'
import { Fab, Tooltip } from '@mui/material'
import EditIcon from '@mui/icons-material/Edit'

import AppContext from '../../../main/AppContext'
import useFetch from '../../../utility/useFetch'
import { IEvent, IParticipantPayload } from '../EventTypes'
import ParticipantsForm from './ParticipantsForm'

function ParticipantsEdit({ event, update } : {
	event: IEvent
	update: () => void
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/api/tournaments/' + event.id + '/teams', 'PUT')

	const onSubmit = (list: Array<IParticipantPayload>) => {
		fetchEdit({ participants: list.map(p => p.id) }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Tooltip title='Edit participants' placement='left' arrow>
			<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, left: 'calc(100vw - 100px)' }}>
				<EditIcon />
			</Fab>
		</Tooltip>
		<ParticipantsForm open={formOpen} list={event.participants} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default ParticipantsEdit
