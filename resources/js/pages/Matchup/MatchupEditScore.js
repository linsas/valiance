import React from 'react'
import { Button } from '@material-ui/core'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import MatchupScoreForm from './MatchupScoreForm'

function MatchupEditScore({ matchup, game, update }) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/api/matchups/' + matchup.id + '/game/' + game.number, 'PUT')

	const onSubmit = (game) => {
		fetchEdit({ score1: game.score1, score2: game.score2 }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button color='primary' onClick={() => setFormOpen(true)}>Edit</Button>
		<MatchupScoreForm open={formOpen} matchup={matchup} game={game} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default MatchupEditScore
