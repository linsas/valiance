import React from 'react'
import { useNavigate } from 'react-router-dom'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { ITeamBasic } from './TeamTypes'

function TeamDelete({ team }: {
	team: ITeamBasic,
}) {
	const context = React.useContext(AppContext)
	const navigate = useNavigate()

	const [isDeleting, fetchDelete] = useFetch('/teams/' + team.id, 'DELETE')

	const handleDelete = () => {
		fetchDelete().then(() => navigate('/Teams'), context.handleFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => handleDelete()}>Delete</Button>
	</>
}

export default TeamDelete
