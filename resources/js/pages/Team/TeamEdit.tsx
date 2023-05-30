import React from 'react'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { ITeamPayload, ITeam } from './TeamTypes'
import TeamForm from './TeamForm'

function TeamEdit({ team, update }: {
	team: ITeam,
	update: () => void,
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState<boolean>(false)
	const [isSaving, fetchEdit] = useFetch('/teams/' + team.id, 'PUT')

	const onSubmit = (team: ITeamPayload) => {
		fetchEdit({ name: team.name }).then(() => update(), context.handleFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => setFormOpen(true)}>Edit</Button>
		<TeamForm open={formOpen} team={team} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default TeamEdit
