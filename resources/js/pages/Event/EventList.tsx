import React from 'react'
import { Paper, Box, Typography, ListItem, ListItemText, Divider, List } from '@mui/material'
import { Skeleton } from '@mui/material'

import eventFormats from '../../data/eventFormats'
import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import ListItemLink from '../../components/ListItemLink'
import { IEventBasic } from './EventTypes'
import EventCreate from './EventCreate'

function EventList() {
	const [eventsList, setEventsList] = React.useState<Array<IEventBasic>>([])
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchEvents] = useFetch<{ data: Array<IEventBasic> }>('/api/tournaments')

	const getEvents = () => {
		fetchEvents().then(response => setEventsList(response.json?.data ?? []), setError)
	}
	React.useEffect(() => getEvents(), [])

	if (isLoading) return <>
		<Skeleton variant='rectangular' height={50} />
		<Box py={1} />
		<Skeleton variant='rectangular' height={250} />
	</>

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (eventsList.length === 0)
		return <Paper>
			<Box p={4} textAlign='center'>
				<Typography variant='h5' color='textSecondary' gutterBottom>
					There are no events yet.
				</Typography>
				<Typography color='textSecondary'>
					Add some and they'll show up here.
				</Typography>
			</Box>
			<EventCreate update={getEvents} />
		</Paper>

	return <Paper>
		<List disablePadding>
			<ListItem>
				<ListItemText style={{ flexBasis: '50%' }}>Name</ListItemText>
				<ListItemText style={{ flexBasis: '50%' }}>Format</ListItemText>
			</ListItem>

			<Divider />

			{eventsList.map((item) => (
				<ListItemLink key={item.id} dense noChevron to={'/Events/' + item.id}>
					<ListItemText style={{ flexBasis: '50%' }}>
						<Typography>
							{item.name}
						</Typography>
					</ListItemText>
					<ListItemText style={{ flexBasis: '50%' }}>
						<Typography variant='body2' color='textSecondary'>
							{eventFormats.reduce((aggr, next) => (next.id === item.format ? next.name : aggr), String(item.format))}
						</Typography>
					</ListItemText>
				</ListItemLink>
			))}
		</List>

		<EventCreate update={getEvents} />
	</Paper>
}

export default EventList
