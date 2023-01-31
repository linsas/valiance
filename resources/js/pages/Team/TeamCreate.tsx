import React from 'react'
import { Fab } from '@mui/material'
import AddIcon from '@mui/icons-material/Add'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { ITeamPayload } from './TeamTypes'
import TeamForm from './TeamForm'

function TeamCreate({ update }: {
	update: () => void,
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState<boolean>(false)
	const [isCreating, fetchCreate] = useFetch('/api/teams', 'POST')

	const onSubmit = (team: ITeamPayload) => {
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
