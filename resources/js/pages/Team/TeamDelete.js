import React from 'react'
import { useHistory } from 'react-router-dom'
import { Button } from '@material-ui/core'

import useFetch from '../../utility/useFetch'

function TeamDelete({ team }) {
	const history = useHistory()

	const [isDeleting, fetchDelete] = useFetch('/api/teams/' + team.id, 'DELETE')

	const handleDelete = () => {
		fetchDelete().then(() => history.push('/Teams'), console.error)
	}

	return <>
		<Button color='primary' onClick={() => handleDelete()}>Delete</Button>
	</>
}

export default TeamDelete
