import React from 'react'
import { useHistory } from 'react-router-dom'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'

function TeamDelete({ team }) {
	const context = React.useContext(AppContext)
	const history = useHistory()

	const [isDeleting, fetchDelete] = useFetch('/api/teams/' + team.id, 'DELETE')

	const handleDelete = () => {
		fetchDelete().then(() => history.push('/Teams'), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button color='primary' onClick={() => handleDelete()}>Delete</Button>
	</>
}

export default TeamDelete
