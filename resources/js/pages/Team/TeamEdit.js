import React from 'react'
import { Button } from '@material-ui/core'

import useFetch from '../../utility/useFetch'
import TeamForm from './TeamForm'

function TeamEdit({ team, update }) {
	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/api/teams/' + team.id, 'PUT')

	const onSubmit = (team) => {
		fetchEdit({ name: team.name }).then(() => update(), console.error)
	}

	return <>
		<Button color='primary' onClick={() => setFormOpen(true)}>Edit</Button>
		<TeamForm open={formOpen} team={team} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default TeamEdit
