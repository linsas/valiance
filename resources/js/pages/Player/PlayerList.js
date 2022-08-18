import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { Paper, Box, Typography, ListItem, ListItemText, Divider, List } from '@material-ui/core'
import { Alert, AlertTitle, Skeleton } from '@material-ui/lab'

import useFetch from '../../utility/useFetch'

function PlayerList() {
	const [playersList, setPlayersList] = React.useState(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchPlayers] = useFetch('/api/players')

	const getPlayers = () => {
		fetchPlayers().then(response => setPlayersList(response.json.data), setError)
	}
	React.useEffect(() => getPlayers(), [])

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

	if (playersList == null || playersList.length === 0)
		return <Paper>
			<Box p={4} textAlign='center'>
				<Typography variant='h5' color='textSecondary' gutterBottom>
					There are no players yet.
				</Typography>
				<Typography color='textSecondary'>
					Add some and they'll show up here.
				</Typography>
			</Box>
		</Paper>

	return <Paper>
		<List disablePadding>
			<ListItem>
				<ListItemText style={{ flexBasis: '60%' }}>Alias</ListItemText>
				<ListItemText style={{ flexBasis: '40%' }}>Team</ListItemText>
			</ListItem>

			<Divider />

			{playersList.map((item) => (
				<ListItem button component={RouterLink} key={item.id} dense to={'/Players/' + item.id}>
					<ListItemText style={{ flexBasis: '60%' }}>
						<Typography>
							{item.alias}
						</Typography>
					</ListItemText>
					<ListItemText style={{ flexBasis: '40%' }}>
						<Typography variant='body2' color='textSecondary'>
							{item.team != null ? item.team.name : null}
						</Typography>
					</ListItemText>
				</ListItem>
			))}
		</List>
	</Paper>
}

export default PlayerList
