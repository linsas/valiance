import React from 'react'
import { Box, Typography, Paper, List, ListItemText } from '@mui/material'
import { Alert, Skeleton } from '@mui/material'

import matchKeys from '../../data/matchKeys'
import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import ListItemLink from '../../components/ListItemLink'
import MatchupGame from './MatchupGame'
import MatchupEditMaps from './MatchupEditMaps'

function Matchup(props) {
	const [matchup, setMatchup] = React.useState(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchMatchup] = useFetch('/api/matchups/' + props.match.params.id)

	const getMatchup = () => {
		fetchMatchup().then(response => setMatchup(response.json.data), setError)
	}
	React.useEffect(() => getMatchup(), [])

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (isLoading) return <>
		<Skeleton variant='rectangular' height={190} />
		<Box py={2} />
		<Skeleton variant='rectangular' height={80} />
	</>

	if (matchup == null) return <Alert severity='warning'>
		There is no data for this matchup.
	</Alert>

	return <>
		<Paper>
			<Box py={2}>
				<Box px={2} pb={1}>
					<Typography color='textSecondary' gutterBottom>Matchup</Typography>
					<Box sx={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', alignItems: 'center', textAlign: 'center' }}>
						<Typography variant='h4'>{matchup.team1.name}</Typography>
						<Typography variant='h5' color='textSecondary'> vs </Typography>
						<Typography variant='h4'>{matchup.team2.name}</Typography>

						<Typography variant='h4'>{matchup.score1}</Typography>
						<Box p={1}>
							<Typography>{matchKeys.reduce((aggr, next) => next.id === matchup.key ? next.name : aggr, matchup.key)}</Typography>
							<Typography color='textSecondary'>Best of {matchup.games.length}</Typography>
						</Box>
						<Typography variant='h4'>{matchup.score2}</Typography>
					</Box>
				</Box>

				<List disablePadding>
					<ListItemLink to={'/Events/' + matchup.tournament.id}>
						<ListItemText>
							<Typography component='span' color='textSecondary'>Event: </Typography>
							<Typography component='span'>{matchup.tournament.name}</Typography>
						</ListItemText>
					</ListItemLink>
				</List>

				<Box px={2} pt={1}>
					<MatchupEditMaps matchup={matchup} update={getMatchup} />
				</Box>
			</Box>
		</Paper>

		{matchup.games.map((game, index) =>
			<MatchupGame key={index} game={game} matchup={matchup} update={getMatchup} />
		)}
	</>
}

export default Matchup
