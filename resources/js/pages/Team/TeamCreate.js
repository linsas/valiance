import React from 'react'
import { Fab } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import TeamForm from './TeamForm'

function TeamCreate({ update }) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isCreating, fetchCreate] = useFetch('/api/teams', 'POST')

	const onSubmit = (team) => {
		fetchCreate({ name: team.name }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, right: 16 }}>
			<AddIcon />
		</Fab>
		<TeamForm open={formOpen} team={{ name: '' }} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default TeamCreate
