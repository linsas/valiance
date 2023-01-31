import React from 'react'
import { Fab } from '@mui/material'
import AddIcon from '@mui/icons-material/Add'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IPlayerPayload } from './PlayerTypes'
import PlayerForm from './PlayerForm'

function PlayerCreate({ update }: {
	update: () => void,
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isCreating, fetchCreate] = useFetch('/api/players', 'POST')

	const onSubmit = (player: IPlayerPayload) => {
		fetchCreate({ alias: player.alias, team: player.team?.id ?? null }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Fab color='primary' onClick={() => setFormOpen(true)} style={{ position: 'fixed', bottom: 16, right: 16 }}>
			<AddIcon />
		</Fab>
		<PlayerForm open={formOpen} player={{ alias: '', team: null }} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default PlayerCreate
