import React from 'react'
import { Fab } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'

import useFetch from '../../utility/useFetch'
import TeamForm from './TeamForm'

function TeamCreate({ update }) {
	const [formOpen, setFormOpen] = React.useState(false)
	const [isCreating, fetchCreate] = useFetch('/api/teams', 'POST')

	const onSubmit = (team) => {
		fetchCreate({ name: team.name }).then(() => update(), console.error)
	}

	return <>
		<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, right: 16 }}>
			<AddIcon />
		</Fab>
		<TeamForm open={formOpen} team={{ name: '' }} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default TeamCreate
