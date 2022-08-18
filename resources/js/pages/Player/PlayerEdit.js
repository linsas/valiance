import React from 'react'
import { Button } from '@material-ui/core'

import useFetch from '../../utility/useFetch'
import PlayerForm from './PlayerForm'

function PlayerEdit({ player, update }) {
	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/api/players/' + player.id, 'PUT')

	const onSubmit = (player) => {
		fetchEdit({ alias: player.alias, team: player.team?.id ?? null }).then(() => update(), console.error)
	}

	return <>
		<Button color='primary' onClick={() => setFormOpen(true)}>Edit</Button>
		<PlayerForm open={formOpen} player={player} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default PlayerEdit
