import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, InputLabel, MenuItem, Select, TextField } from '@mui/material'

import { IEventPayload } from './EventTypes'
import eventFormats from '../../data/eventFormats'

function EventForm({ open, event: defaultEvent, onSubmit, onClose }: {
	open: boolean
	event: IEventPayload
	onSubmit: (event: IEventPayload) => void
	onClose: () => void
}) {
	const [event, setEvent] = React.useState<IEventPayload>(defaultEvent)

	React.useEffect(() => {
		if (!open) return
		setEvent(defaultEvent)
	}, [open])

	const changeName = (name: string) => setEvent(p => ({ ...p, name: name }))
	const changeFormat = (format: number) => setEvent(p => ({ ...p, format: format }))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Event</DialogTitle>
			<DialogContent>
				<TextField
					autoFocus
					variant='filled'
					margin='normal'
					label='Name'
					type='text'
					value={event.name}
					onChange={event => changeName(event.target.value)}
					fullWidth
				/>
				<FormControl variant='filled' margin='normal' fullWidth>
					<InputLabel id='format-select-label'>Format</InputLabel>
					<Select
						labelId='format-select-label'
						value={event.format}
						onChange={event => changeFormat(Number(event.target.value))}
					>
						{eventFormats.map(f => <MenuItem key={f.id} value={f.id}>{f.name}</MenuItem>)}
					</Select>
				</FormControl>
			</DialogContent>
			<DialogActions>
				<Button onClick={onClose}>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(event)}>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default EventForm
