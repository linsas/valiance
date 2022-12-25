import React from 'react'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import MatchupMapForm from './MatchupMapForm'

function MatchupEditMaps({ matchup, update }) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/api/matchups/' + matchup.id, 'PUT')

	const onSubmit = (list) => {
		fetchEdit({ maps: list }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => setFormOpen(true)}>Edit</Button>
		<MatchupMapForm open={formOpen} matchup={matchup} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default MatchupEditMaps
