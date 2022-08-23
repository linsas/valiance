import React from 'react'
import { Button } from '@material-ui/core'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import EventForm from './EventForm'

function EventEdit({ event, update }) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/api/tournaments/' + event.id, 'PUT')

	const onSubmit = (item) => {
		fetchEdit({ name: item.name, format: item.format }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button color='primary' onClick={() => setFormOpen(true)}>Edit</Button>
		<EventForm open={formOpen} event={event} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default EventEdit