import React from 'react'
import { useNavigate } from 'react-router-dom'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IEvent } from './EventTypes'

function EventDelete({ event }: {
	event: IEvent
}) {
	const context = React.useContext(AppContext)
	const navigate = useNavigate()

	const [isDeleting, fetchDelete] = useFetch('/tournaments/' + event.id, 'DELETE')

	const handleDelete = () => {
		fetchDelete().then(() => navigate('/Events'), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => handleDelete()}>Delete</Button>
	</>
}

export default EventDelete
