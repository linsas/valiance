import React from 'react'
import { Fab, Tooltip } from '@material-ui/core'
import EditIcon from '@material-ui/icons/Edit'

import AppContext from '../../../main/AppContext'
import useFetch from '../../../utility/useFetch'
import ParticipantsForm from './ParticipantsForm'

function ParticipantsEdit({ event, update }) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/api/tournaments/' + event.id + '/teams', 'PUT')

	const onSubmit = (list) => {
		fetchEdit({ participants: list.map(p => p.id) }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Tooltip title='Edit participants' arrow>
			<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, left: 'calc(100vw - 100px)' }}>
				<EditIcon />
			</Fab>
		</Tooltip>
		<ParticipantsForm open={formOpen} list={event.participants} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default ParticipantsEdit
