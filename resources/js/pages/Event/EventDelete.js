import React from 'react'
import { useHistory } from 'react-router-dom'
import { Button } from '@material-ui/core'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'

function EventDelete({ event }) {
	const context = React.useContext(AppContext)
	const history = useHistory()

	const [isDeleting, fetchDelete] = useFetch('/api/tournaments/' + event.id, 'DELETE')

	const handleDelete = () => {
		fetchDelete().then(() => history.push('/Events'), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button color='primary' onClick={() => handleDelete()}>Delete</Button>
	</>
}

export default EventDelete
