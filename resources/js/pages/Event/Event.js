import React from 'react'
import { Box, Typography, Paper, List, ListItem, ListItemText, Divider } from '@material-ui/core'
import { Alert, Skeleton } from '@material-ui/lab'

import eventFormats from '../../data/eventFormats'
import useFetch from '../../utility/useFetch'
import AlertError from '../../components/AlertError'
import EventEdit from './EventEdit'
import EventDelete from './EventDelete'
import Participants from './Participants/Participants'

function Section({ name }) {
	return <Box display='flex' alignItems='center' gridGap={15} my={2}>
		<Divider style={{ flexGrow: 1 }} />
		<Typography color='textSecondary'>{name}</Typography>
		<Divider style={{ flexGrow: 1 }} />
	</Box>
}

function Event(props) {
	const [event, setEvent] = React.useState(null)
	const [errorFetch, setError] = React.useState(null)
	const [isLoading, fetchEvent] = useFetch('/api/tournaments/' + props.match.params.id)

	const getEvent = () => {
		fetchEvent().then(response => setEvent(response.json.data), err => setError(err))
	}
	React.useEffect(() => getEvent(), [])

	if (errorFetch != null) return <AlertError error={errorFetch} />

	if (isLoading) return <>
		<Skeleton variant='rect' height={150} />
		<Box py={1} />
		<Skeleton variant='rect' height={50} />
	</>

	if (event == null) return <Alert severity='warning'>
		There is no data for this event.
	</Alert>

	return <>
		<Paper>
			<Box py={2}>
				<Box px={2} pb={1}>
					<Typography color='textSecondary' gutterBottom>Event</Typography>
					<Typography variant='h4'>{event.name}</Typography>
				</Box>

				<List disablePadding>
					<ListItem>
						<ListItemText>
							<Typography component='span' color='textSecondary'>Format: </Typography>
							<Typography component='span'>{eventFormats.find(f => f.id === event.format).name}</Typography>
						</ListItemText>
					</ListItem>
				</List>

				<Box px={2} pt={1}>
					<EventEdit event={event} update={getEvent} />
					<EventDelete event={event} />
				</Box>
			</Box>
		</Paper>

		<Section name='Participants' />
		<Participants event={event} update={getEvent} />

	</>
}

export default Event
