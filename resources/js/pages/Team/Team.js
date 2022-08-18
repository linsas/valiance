import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { Box, Typography, Paper, List, ListItem, ListItemText } from '@material-ui/core'
import { Alert, AlertTitle, Skeleton } from '@material-ui/lab'

import useFetch from '../../utility/useFetch'
import TeamEdit from './TeamEdit'
import TeamDelete from './TeamDelete'

function TeamPlayers({ team }) {
	if (team.players.length === 0) return <Paper>
		<Box my={2} p={2}>
			<Typography align='center' color='textSecondary'>This team does not have any players.</Typography>
		</Box>
	</Paper>

	return <>
		<Paper>
			<List>
				<ListItem>
					<ListItemText>Players:</ListItemText>
				</ListItem>
				{team.players.map((player) =>
					<ListItem button component={RouterLink} key={player.id} to={'/Players/' + player.id}>
						<ListItemText>
							<Typography component='span'>{player.alias}</Typography>
						</ListItemText>
					</ListItem>
				)}
			</List>
		</Paper>
	</>
}

function Team(props) {
	const [team, setTeam] = React.useState(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchTeam] = useFetch('/api/teams/' + props.match.params.id)

	const getTeam = () => {
		fetchTeam().then(response => setTeam(response.json.data), setError)
	}
	React.useEffect(() => getTeam(), [])

	if (errorFetch != null) {
		if (errorFetch.name === 'ResponseNotOkError') {
			return <Alert severity='error'>
				<AlertTitle>{errorFetch.result.status} {errorFetch.result.statusText}</AlertTitle>
				{errorFetch.result?.json?.message ?? 'That\'s an error.'}
			</Alert>
		}
		return <Alert severity='error'>{errorFetch.message}</Alert>
	}

	if (isLoading) return <>
		<Skeleton variant='rect' height={150} />
		<Box py={1} />
		<Skeleton variant='rect' height={50} />
	</>

	if (team == null) return <Alert severity='warning'>
		There is no data for this team.
	</Alert>

	return <>
		<Paper>
			<Box py={2}>
				<Box px={2} pb={1}>
					<Typography color='textSecondary' gutterBottom>Team</Typography>
					<Typography variant='h4'>{team.name}</Typography>
				</Box>

				<Box px={2} pt={1}>
					<TeamEdit team={team} update={getTeam} />
					<TeamDelete team={team} />
				</Box>
			</Box>
		</Paper>

		<Box my={2} />
		<TeamPlayers team={team} />

	</>
}

export default Team
