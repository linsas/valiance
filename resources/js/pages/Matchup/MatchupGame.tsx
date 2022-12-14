import React from 'react'
import { useTheme, lighten, darken } from '@mui/material'
import { Box, Typography, Paper, Divider, Grid } from '@mui/material'

import mapList from '../../data/maps'
import MatchupEditScore from './MatchupEditScore'

function MatchupGameMap({ gameMap }) {
	const map = mapList.find(map => map.id === gameMap)
	const mapName = gameMap == null ? 'Undecided' : map.name
	const mapColor = gameMap == null ? '#808080' : map.color

	const theme = useTheme()
	const bgColor = theme.palette.mode === 'light' ? lighten(mapColor, 0.8) : darken(mapColor, 0.8)

	return <Box style={{ backgroundColor: bgColor }} sx={{ textAlign: 'center', padding: 0.5 }}>
		<Typography color={gameMap == null ? 'textSecondary' : undefined}>
			{mapName}
		</Typography>
	</Box>
}

function MatchupGame({ matchup, game, update }) {
	return <>
		<Paper square sx={{ marginTop: 4 }}>
			<MatchupGameMap gameMap={game.map} />
			<Divider />

			<Box p={1}>
				<Grid container>
					<Grid item xs={4} style={{ textAlign: 'left' }}>
						<Typography variant='caption'>{matchup.team1.name}</Typography>
					</Grid>
					<Grid item xs={4} style={{ textAlign: 'center' }}>
						{(game.score1 != null && game.score2 != null) ? (
							<Typography variant='h5'>{game.score1}:{game.score2}</Typography>
						) : (
							<Typography color='textSecondary'>Not played</Typography>
						)}
					</Grid>
					<Grid item xs={4} style={{ textAlign: 'right' }}>
						<Typography variant='caption'>{matchup.team2.name}</Typography>
					</Grid>
				</Grid>
			</Box>

		</Paper>
		<MatchupEditScore matchup={matchup} game={game} update={update} />
	</>
}

export default MatchupGame
