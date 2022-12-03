import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, InputLabel, MenuItem, Select, TextField } from '@mui/material'

import eventFormats from '../../data/eventFormats'

function EventForm({ open, event: defaultEvent, onSubmit, onClose }) {
	if (defaultEvent == null) defaultEvent = {}
	const [event, setEvent] = React.useState(defaultEvent)

	React.useEffect(() => {
		if (!open) return
		setEvent(defaultEvent)
	}, [open])

	const changeName = name => setEvent(p => ({ ...p, name: name }))
	const changeFormat = format => setEvent(p => ({ ...p, format: format }))

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
				<FormControl fullWidth margin='normal'>
					<InputLabel variant='filled' id='format-select-label'>Format</InputLabel>
					<Select
						variant='filled'
						labelId='format-select-label'
						id='format-select'
						value={event.format || ''}
						onChange={event => changeFormat(event.target.value)}
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
