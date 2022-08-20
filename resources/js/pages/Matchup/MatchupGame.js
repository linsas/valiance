import React from 'react'
import { Box, Typography, Paper, Divider, Grid, makeStyles, useTheme, lighten, darken } from '@material-ui/core'

import mapList from '../../data/maps'

const useStyles = makeStyles(theme => ({
	root: {
		marginTop: theme.spacing(4),
	},
	map: {
		textAlign: 'center',
		padding: 4,
	},
}))

function MatchupGameMap({ gameMap, className }) {
	const map = mapList.find(map => map.id === gameMap)
	const mapName = gameMap == null ? 'Undecided' : map.name
	const mapColor = gameMap == null ? '#808080' : map.color

	const theme = useTheme()
	const bgColor = theme.palette.type === 'light' ? lighten(mapColor, 0.8) : darken(mapColor, 0.8)

	return <div className={className} style={{ backgroundColor: bgColor }}>
		<Typography color={gameMap == null ? 'textSecondary' : undefined}>
			{mapName}
		</Typography>
	</div>
}

function MatchupGame({ matchup, game }) {
	const styles = useStyles()

	return <>
		<Paper square className={styles.root}>
			<MatchupGameMap className={styles.map} gameMap={game.map} />
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
	</>
}

export default MatchupGame
