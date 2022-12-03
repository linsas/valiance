import React from 'react'
import { Container as DndContainer, Draggable } from '@edorivai/react-smooth-dnd'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, IconButton, List, ListItem, ListItemIcon, ListItemSecondaryAction, ListItemText, TextField } from '@mui/material'
import { Autocomplete } from '@mui/material'
import DragHandleIcon from '@mui/icons-material/DragHandle'
import DeleteIcon from '@mui/icons-material/Delete'

import AppContext from '../../../main/AppContext'
import useFetch from '../../../utility/useFetch'

function ParticipantsForm({ open, list, onSubmit, onClose }) {
	const context = React.useContext(AppContext)

	const [items, setItems] = React.useState([])

	React.useEffect(() => {
		if (!open) return
		setItems(list.map(p => ({ id: p.team.id, name: p.name })))
	}, [open])

	const [teamsList, setTeamsList] = React.useState(null)
	const [searchValue, setSearchValue] = React.useState('')
	const [isLoadingTeams, fetchTeams] = useFetch('/api/teams')

	React.useEffect(() => {
		if (!open) return
		if (isLoadingTeams) return
		if (teamsList != null) return
		fetchTeams().then(response => setTeamsList(response.json.data), context.notifyFetchError)
	}, [open])

	const onDrop = ({ removedIndex, addedIndex }) => {
		let newItems = items.slice()
		newItems.splice(addedIndex, 0, newItems.splice(removedIndex, 1)[0])
		setItems(newItems)
	}

	const shuffle = () => {
		let newItems = items.slice()
		for (let i = 0; i < newItems.length - 1; i++) {
			const j = Math.floor(Math.random() * (newItems.length - i)) + i
			if (i === j) continue
			[newItems[i], newItems[j]] = [newItems[j], newItems[i]]
		}
		setItems(newItems)
	}

	const remove = (removedIndex) => {
		let newItems = items.slice()
		newItems.splice(removedIndex, 1)
		setItems(newItems)
	}

	const insert = (option) => {
		if (option == null) return
		let newItems = items.concat([option])
		setSearchValue('')
		setItems(newItems)
	}

	return <>
		<Dialog open={open} fullWidth disableEnforceFocus>
			<DialogTitle>Event participants</DialogTitle>
			<DialogContent>
				<Autocomplete
					options={teamsList ?? []}
					value={null}
					inputValue={searchValue}
					getOptionLabel={option => option.name || ''}
					getOptionDisabled={option => items.find(i => i.id === option.id) != null}
					onInputChange={(_event, text) => setSearchValue(text)}
					onChange={(_event, option) => insert(option)}
					blurOnSelect
					fullWidth
					renderInput={params => <TextField {...params} variant='filled' label='Add a team' />}
				/>
				<List>
					<DndContainer dragHandleSelector='.drag-handle' onDrop={onDrop} lockAxis='y' style={{ border: '1px solid dimgray' }}>
						{items.map((participant, index) => (
							<Draggable key={participant.id}>
								<ListItem>
									<ListItemIcon className='drag-handle'>
										<DragHandleIcon />
									</ListItemIcon>
									<ListItemText primary={participant.name} />
									<ListItemSecondaryAction>
										<IconButton onClick={() => remove(index)}>
											<DeleteIcon />
										</IconButton>
									</ListItemSecondaryAction>
								</ListItem>
							</Draggable>
						))}
					</DndContainer>
				</List>
			</DialogContent>
			<DialogActions>
				<Button onClick={onClose} color='primary'>Cancel</Button>
				<Button onClick={shuffle} color='primary'>Shuffle</Button>
				<Button type='submit' onClick={() => onSubmit(items)} color='primary'>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default ParticipantsForm
