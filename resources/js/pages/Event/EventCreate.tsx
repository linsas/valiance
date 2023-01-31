import React from 'react'
import { Fab } from '@mui/material'
import AddIcon from '@mui/icons-material/Add'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IEventPayload } from './EventTypes'
import EventForm from './EventForm'

function EventCreate({ update }: {
	update: () => void
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isCreating, fetchCreate] = useFetch('/api/tournaments', 'POST')

	const onSubmit = (item: IEventPayload) => {
		fetchCreate({ name: item.name, format: item.format }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, right: 16 }}>
			<AddIcon />
		</Fab>
		<EventForm open={formOpen} event={{ name: '', format: 1 }} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default EventCreate
