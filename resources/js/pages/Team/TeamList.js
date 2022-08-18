import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { Paper, Box, Typography, ListItem, ListItemText, Divider, List } from '@material-ui/core'
import { Alert, AlertTitle, Skeleton } from '@material-ui/lab'

import useFetch from '../../utility/useFetch'
import TeamCreate from './TeamCreate'

function TeamList() {
	const [teamsList, setTeamsList] = React.useState(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchTeams] = useFetch('/api/teams')

	const getTeams = () => {
		fetchTeams().then(response => setTeamsList(response.json.data), setError)
	}
	React.useEffect(() => getTeams(), [])

	if (isLoading) return <>
		<Skeleton variant='rect' height={50} />
		<Box py={1} />
		<Skeleton variant='rect' height={250} />
	</>

	if (errorFetch != null) {
		if (errorFetch.name === 'ResponseNotOkError') {
			return <Alert severity='error'>
				<AlertTitle>{errorFetch.result.status} {errorFetch.result.statusText}</AlertTitle>
				{errorFetch.result?.json?.message ?? 'That\'s an error.'}
			</Alert>
		}
		return <Alert severity='error'>{errorFetch.message}</Alert>
	}

	if (teamsList == null || teamsList.length === 0)
		return <Paper>
			<Box p={4} textAlign='center'>
				<Typography variant='h5' color='textSecondary' gutterBottom>
					There are no teams yet.
				</Typography>
				<Typography color='textSecondary'>
					Add some and they'll show up here.
				</Typography>
			</Box>
			<TeamCreate update={getTeams} />
		</Paper>

	return <Paper>
		<List disablePadding>
			<ListItem>
				<ListItemText>Name</ListItemText>
			</ListItem>

			<Divider />

			{teamsList.map((item) => (
				<ListItem button component={RouterLink} key={item.id} dense to={'/Teams/' + item.id}>
					<ListItemText>
						<Typography>
							{item.name}
						</Typography>
					</ListItemText>
				</ListItem>
			))}
		</List>

		<TeamCreate update={getTeams} />
	</Paper>
}

export default TeamList
