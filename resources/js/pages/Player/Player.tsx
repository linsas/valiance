import React from 'react'
import { useParams } from 'react-router-dom'
import { Box, Typography, Paper, List, ListItem, ListItemText } from '@mui/material'
import { Alert, Skeleton } from '@mui/material'

import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import ListItemLink from '../../components/ListItemLink'
import { IPlayer, IPlayerTransfer } from './PlayerTypes'
import PlayerEdit from './PlayerEdit'
import PlayerDelete from './PlayerDelete'

function PlayerParticipations({ player }: {
	player: IPlayer,
}) {
	if (player.participations.length === 0) return <Paper>
		<Box my={2} p={2}>
			<Typography align='center' color='textSecondary'>This player has not participated in any events.</Typography>
		</Box>
	</Paper>

	return <>
		<Paper>
			<List>
				<ListItem>
					<ListItemText>Participations:</ListItemText>
				</ListItem>
				{player.participations.map((participation) =>
					<ListItemLink key={participation.tournament.id} to={'/Events/' + participation.tournament.id}>

						<ListItemText>
							<Typography component='span' color='textSecondary'>As part of </Typography>
							<Typography component='span'>{participation.team.name}</Typography>
							<Typography component='span' color='textSecondary'> during </Typography>
							<Typography component='span'>{participation.tournament.name}</Typography>
						</ListItemText>

					</ListItemLink>
				)}
			</List>
		</Paper>
	</>
}

function PlayerTransfer({ transfer, earlier }: {
	transfer: IPlayerTransfer,
	earlier: IPlayerTransfer | null,
}) {

	if (transfer.team == null) {
		if (earlier?.team == null) return <ListItem>
			<ListItemText>
				<Typography component='span'>{transfer.date}:</Typography>{' '}
				<Typography component='span' color='textSecondary'>Not in a team</Typography>
				{/* this shouldn't happen; earlier team should always be set */}
			</ListItemText>
		</ListItem>

		return <ListItem>
			<ListItemText>
				<Typography component='span'>{transfer.date}:</Typography>{' '}
				<Typography component='span' color='textSecondary'>Left {earlier.team.name}</Typography>
			</ListItemText>
		</ListItem>
	}

	return <ListItemLink to={'/Teams/' + transfer.team.id}>
		<ListItemText>
			{transfer.date}:{' '}
			<Typography component='span' color='textSecondary'>{earlier?.team == null ? 'Joined' : 'Transferred to'}</Typography>{' '}
			{transfer.name !== transfer.team.name ?
				<>
					{transfer.name}{' '}
					<Typography component='span' variant='body2' color='textSecondary'>(now known as
						{' '}
						<Typography component='span' variant='body2' color='textPrimary'>{transfer.team.name}</Typography>
					)</Typography>
				</>
			:
				<>{transfer.team.name}</>
			}
		</ListItemText>
	</ListItemLink>
}

function PlayerTeamHistory({ player }: {
	player: IPlayer,
}) {
	if (player.history.length === 0) return null

	return <>
		<Paper>
			<List>
				<ListItem>
					<ListItemText>Team history:</ListItemText>
				</ListItem>
				{player.history.map((transfer, index, array) =>
					<PlayerTransfer key={index} transfer={transfer} earlier={index != array.length - 1 ? array[index + 1] : null} />
				)}
			</List>
		</Paper>
	</>
}

function Player() {
	const routeParams = useParams<{ id: string }>()

	const [player, setPlayer] = React.useState<IPlayer | null>(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchPlayer] = useFetch<IPlayer>('/players/' + routeParams.id)

	const getPlayer = () => {
		fetchPlayer().then(response => setPlayer(response?.data ?? null), setError)
	}
	React.useEffect(() => getPlayer(), [])

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (isLoading) return <>
		<Skeleton variant='rectangular' height={150} />
		<Box py={1} />
		<Skeleton variant='rectangular' height={50} />
		<Box py={1} />
		<Skeleton variant='rectangular' height={50} />
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
						<ListItemLink to={'/Teams/' + player.team.id}>
							<ListItemText>
								<Typography component='span' color='textSecondary'>Current team: </Typography>
								<Typography component='span'>{player.team.name}</Typography>
							</ListItemText>
						</ListItemLink>
					)}
				</List>

				<Box px={2} pt={1}>
					<PlayerEdit player={player} update={getPlayer} />
					<PlayerDelete player={player} />
				</Box>
			</Box>
		</Paper>

		<Box my={2} />
		<PlayerParticipations player={player} />

		<Box my={2} />
		<PlayerTeamHistory player={player} />

	</>
}

export default Player
