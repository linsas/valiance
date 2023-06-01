import React from 'react'
import { Fab, Tooltip } from '@mui/material'
import PlayerIcon from '@mui/icons-material/Person'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import TeamPlayersForm from './TeamPlayersForm'
import { ITeam, ITeamPlayer } from './TeamTypes'

function TeamPlayersEdit({ team, update } : {
	team: ITeam
	update: () => void
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/teams/' + team.id + '/players', 'PUT')

	const onSubmit = (list: Array<ITeamPlayer>) => {
		fetchEdit({ players: list.map(p => p.id) }).then(() => update(), context.handleFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Tooltip title='Edit players' placement='left' arrow>
			<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, right: 16 }}>
				<PlayerIcon />
			</Fab>
		</Tooltip>
		<TeamPlayersForm open={formOpen} list={team.players} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default TeamPlayersEdit
