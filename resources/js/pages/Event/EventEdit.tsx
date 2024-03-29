import React from 'react'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IEvent, IEventPayload } from './EventTypes'
import EventForm from './EventForm'

function EventEdit({ event, update }: {
	event: IEvent
	update: () => void
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/tournaments/' + event.id, 'PUT')

	const onSubmit = (item: IEventPayload) => {
		fetchEdit({ name: item.name, format: item.format }).then(() => update(), context.handleFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => setFormOpen(true)}>Edit</Button>
		<EventForm open={formOpen} event={event} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default EventEdit
