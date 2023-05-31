import React from 'react'
import { Paper, Box, Typography, ListItem, ListItemText, Divider, List } from '@mui/material'
import { Skeleton } from '@mui/material'

import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import ListItemLink from '../../components/ListItemLink'
import { IPlayerBasic } from './PlayerTypes'
import PlayerCreate from './PlayerCreate'

function PlayerList() {
	const [playersList, setPlayersList] = React.useState<Array<IPlayerBasic>>([])
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchPlayers] = useFetch<Array<IPlayerBasic>>('/players')

	const getPlayers = () => {
		fetchPlayers().then(response => setPlayersList(response?.data ?? []), setError)
	}
	React.useEffect(() => getPlayers(), [])

	if (isLoading) return <>
		<Skeleton variant='rectangular' height={50} />
		<Box py={1} />
		<Skeleton variant='rectangular' height={250} />
	</>

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (playersList.length === 0)
		return <Paper>
			<Box p={4} textAlign='center'>
				<Typography variant='h5' color='textSecondary' gutterBottom>
					There are no players yet.
				</Typography>
				<Typography color='textSecondary'>
					Add some and they'll show up here.
				</Typography>
			</Box>
			<PlayerCreate update={getPlayers} />
		</Paper>

	return <Paper>
		<List disablePadding>
			<ListItem>
				<ListItemText style={{ flexBasis: '60%' }}>Alias</ListItemText>
				<ListItemText style={{ flexBasis: '40%' }}>Team</ListItemText>
			</ListItem>

			<Divider />

			{playersList.map((item) => (
				<ListItemLink key={item.id} dense noChevron to={'/Players/' + item.id}>
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
				</ListItemLink>
			))}
		</List>

		<PlayerCreate update={getPlayers} />
	</Paper>
}

export default PlayerList
