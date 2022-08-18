import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { Box, Typography, Paper, List, ListItem, ListItemText } from '@material-ui/core'
import { Alert, Skeleton } from '@material-ui/lab'

import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'

function Player(props) {
	const [player, setPlayer] = React.useState(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchPlayer] = useFetch('/api/players/' + props.match.params.id)

	const getPlayer = () => {
		fetchPlayer().then(response => setPlayer(response.json.data), setError)
	}
	React.useEffect(() => getPlayer(), [])

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (isLoading) return <>
		<Skeleton variant='rect' height={150} />
		<Box py={1} />
		<Skeleton variant='rect' height={50} />
	</>

	if (player == null) return <Alert severity='warning'>
		There is no data for this player.
	</Alert>

	return <>
		<Paper>
			<Box py={2}>
				<Box px={2} pb={1}>
					<Typography color='textSecondary' gutterBottom>Player</Typography>
					<Typography variant='h4'>{player.alias}</Typography>
				</Box>

				<List disablePadding>
					{player.team == null ? (<>
						<ListItem>
							<ListItemText>
								<Typography component='span' color='textSecondary'>This player is not currently in a team</Typography>
							</ListItemText>
						</ListItem>
					</>) : (
						<ListItem button component={RouterLink} to={'/Teams/' + player.team.id}>
							<ListItemText>
								<Typography component='span' color='textSecondary'>Team: </Typography>
								<Typography component='span'>{player.team.name}</Typography>
							</ListItemText>
						</ListItem>
					)}
				</List>
			</Box>
		</Paper>
	</>
}

export default Player
