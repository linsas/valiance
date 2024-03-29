import React from 'react'
import { Box, Button, Dialog, DialogActions, DialogContent, DialogTitle, TextField } from '@mui/material'
import { IGame, IMatchup } from './MatchupTypes'

function MatchupScoreForm({ open, matchup, game: defaultGame, onSubmit, onClose }:{
	open: boolean
	matchup: IMatchup
	game: IGame
	onSubmit: (game: IGame) => void
	onClose: () => void
}) {
	const [game, setGame] = React.useState(defaultGame)

	React.useEffect(() => {
		if (!open) return
		setGame(defaultGame)
	}, [open])

	const changeScore1 = (score: string) => setGame(g => ({ ...g, score1: score }))
	const changeScore2 = (score: string) => setGame(g => ({ ...g, score2: score }))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Game {game.number} Score</DialogTitle>
			<DialogContent>

				<Box display='grid' gridAutoFlow='column' gridTemplateColumns='1fr auto 1fr' gap={2} alignItems='end'>
					<TextField
						variant='filled'
						autoFocus
						fullWidth
						margin='dense'
						label={matchup.team1.name + ' score'}
						type='text'
						value={game.score1 != null ? game.score1 : ''}
						onChange={event => changeScore1(event.target.value)}
					/>
					<span style={{ alignSelf: 'end' }}>:</span>
					<TextField
						variant='filled'
						fullWidth
						margin='dense'
						label={matchup.team2.name + ' score'}
						type='text'
						value={game.score2 != null ? game.score2 : ''}
						onChange={event => changeScore2(event.target.value)}
					/>
				</Box>

			</DialogContent>
			<DialogActions>
				<Button onClick={onClose}>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(game)}>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default MatchupScoreForm
