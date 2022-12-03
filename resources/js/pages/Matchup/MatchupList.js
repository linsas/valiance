import React from 'react'
import { Paper, Box, Typography, ListItem, ListItemText, Divider, List } from '@mui/material'
import { Skeleton } from '@mui/material'

import mapList from '../../data/maps'
import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import ListItemLink from '../../components/ListItemLink'

function MatchupList() {
	const [matchupList, setMatchupsList] = React.useState(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchMatchups] = useFetch('/api/matchups')

	const getMatchups = () => {
		fetchMatchups().then(response => setMatchupsList(response.json.data), setError)
	}
	React.useEffect(() => getMatchups(), [])

	if (isLoading) return <>
		<Skeleton variant='rect' height={50} />
		<Box py={1} />
		<Skeleton variant='rect' height={250} />
	</>

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (matchupList == null || matchupList.length === 0)
		return <Paper>
			<Box p={4} textAlign='center'>
				<Typography variant='h5' color='textSecondary' gutterBottom>
					There are no matchups yet.
				</Typography>
				<Typography color='textSecondary'>
					Create and run some events and they'll show up here.
				</Typography>
			</Box>
		</Paper>

	return <Paper>
		<List disablePadding>
			<ListItem>
				<ListItemText style={{ flexBasis: '45%' }}>Teams</ListItemText>
				<ListItemText style={{ flexBasis: '35%' }}>Event</ListItemText>
				<ListItemText style={{ flexBasis: '20%' }}>Maps</ListItemText>
			</ListItem>

			<Divider />

			{matchupList.map((item) => (
				<ListItemLink key={item.id} dense noChevron to={'/Matchups/' + item.id}>

					<ListItemText style={{ flexBasis: '45%' }}>
						<Typography component='span'>{item.team1}</Typography>
						<Typography component='span' color='textSecondary'> vs </Typography>
						<Typography component='span'>{item.team2}</Typography>
					</ListItemText>
					<ListItemText style={{ flexBasis: '35%' }}>
						<Typography variant='body2' color='textSecondary'>
							{item.tournament}
						</Typography>
					</ListItemText>
					<ListItemText style={{ flexBasis: '20%' }}>
						<Typography variant='body2' color='textSecondary'>
							{item.maps.map(mapCode => mapList.reduce((aggr, next) => (next.id === mapCode ? next.name : aggr), mapCode)).filter(m => m != null).join(', ')}
						</Typography>
					</ListItemText>

				</ListItemLink>
			))}
		</List>

	</Paper>
}

export default MatchupList
