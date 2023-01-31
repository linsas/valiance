import React from 'react'
import { useHistory } from 'react-router-dom'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { ITeamBasic } from './TeamTypes'

function TeamDelete({ team }: {
	team: ITeamBasic,
}) {
	const context = React.useContext(AppContext)
	const history = useHistory()

	const [isDeleting, fetchDelete] = useFetch('/api/teams/' + team.id, 'DELETE')

	const handleDelete = () => {
		fetchDelete().then(() => history.push('/Teams'), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => handleDelete()}>Delete</Button>
	</>
}

export default TeamDelete
