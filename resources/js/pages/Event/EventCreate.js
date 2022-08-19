import React from 'react'
import { Fab } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import EventForm from './EventForm'

function EventCreate({ update }) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isCreating, fetchCreate] = useFetch('/api/tournaments', 'POST')

	const onSubmit = (item) => {
		fetchCreate({ name: item.name, format: item.format }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, right: 16 }}>
			<AddIcon />
		</Fab>
		<EventForm open={formOpen} event={{ name: '', format: null }} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default EventCreate
