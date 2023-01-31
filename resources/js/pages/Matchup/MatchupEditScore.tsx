import React from 'react'
import { Button } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IGame, IMatchup } from './MatchupTypes'
import MatchupScoreForm from './MatchupScoreForm'

function MatchupEditScore({ matchup, game, update }: {
	matchup: IMatchup,
	game: IGame,
	update: () => void
}) {
	const context = React.useContext(AppContext)

	const [formOpen, setFormOpen] = React.useState(false)
	const [isSaving, fetchEdit] = useFetch('/api/matchups/' + matchup.id + '/game/' + game.number, 'PUT')

	const onSubmit = (game: IGame) => {
		fetchEdit({ score1: game.score1, score2: game.score2 }).then(() => update(), context.notifyFetchError)
	}

	if (context.jwt == null) return null

	return <>
		<Button onClick={() => setFormOpen(true)}>Edit</Button>
		<MatchupScoreForm open={formOpen} matchup={matchup} game={game} onSubmit={onSubmit} onClose={() => setFormOpen(false)} />
	</>
}

export default MatchupEditScore
