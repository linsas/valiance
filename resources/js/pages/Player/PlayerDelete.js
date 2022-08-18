import React from 'react'
import { useHistory } from 'react-router-dom'
import { Button } from '@material-ui/core'

import useFetch from '../../utility/useFetch'

function PlayerDelete({ player }) {
	const history = useHistory()

	const [isDeleting, fetchDelete] = useFetch('/api/players/' + player.id, 'DELETE')

	const handleDelete = () => {
		fetchDelete().then(() => history.push('/Players'), console.error)
	}

	return <>
		<Button color='primary' onClick={() => handleDelete()}>Delete</Button>
	</>
}

export default PlayerDelete
