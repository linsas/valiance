import React from 'react'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IPlayer, IPlayerPayload } from './PlayerTypes'
import PlayerForm from './PlayerForm'

function PlayerEdit({ player, update } :{
	player: IPlayer,
	update: () => void,
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/players/' + player.id, 'PUT')

	const onSubmit = (player: IPlayerPayload) => {
		fetchEdit({ alias: player.alias, team: player.team?.id ?? null }).then(() => update(), context.handleFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => setFormOpen(true)}>Edit</Button>
		<PlayerForm open={formOpen} player={player} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default PlayerEdit
