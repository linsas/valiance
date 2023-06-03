import React from 'react'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IGameMap, IMatchup } from './MatchupTypes'
import MatchupMapForm from './MatchupMapForm'

function MatchupEditMaps({ matchup, update }: {
	matchup: IMatchup,
	update: () => void
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/matchups/' + matchup.id, 'PUT')

	const onSubmit = (list: Array<IGameMap | null>) => {
		fetchEdit({ maps: list.map(map => map?.id ?? null) }).then(() => update(), context.handleFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => setFormOpen(true)}>Edit</Button>
		<MatchupMapForm open={formOpen} matchup={matchup} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default MatchupEditMaps
