import React from 'react'
import { Container as DndContainer, Draggable } from '@edorivai/react-smooth-dnd'
import { Box, Button, Dialog, DialogActions, DialogContent, DialogTitle, IconButton, List, ListItem, ListItemIcon, ListItemSecondaryAction, ListItemText, TextField, Typography } from '@mui/material'
import { Autocomplete } from '@mui/material'
import DragHandleIcon from '@mui/icons-material/DragHandle'
import DeleteIcon from '@mui/icons-material/Delete'

import AppContext from '../../../main/AppContext'
import useFetch from '../../../utility/useFetch'
import { ITeamBasic } from '../../Team/TeamTypes'
import { IParticipant, IFormParticipant } from '../EventTypes'

function ParticipantsForm({ open, list, onSubmit, onClose }: {
	open: boolean
	list: Array<IParticipant>
	onSubmit: (list: Array<IFormParticipant>) => void
	onClose: () => void
}) {
	const context = React.useContext(AppContext)

	const [items, setItems] = React.useState<Array<IFormParticipant>>([])

	React.useEffect(() => {
		if (!open) return
		setItems(list.map(p => ({
			team: {
				id: p.team.id,
				name: p.team.name,
			},
			name: p.name,
		})))
	}, [open])

	const [teamsList, setTeamsList] = React.useState<Array<ITeamBasic> | null>(null)
	const [searchValue, setSearchValue] = React.useState('')
	const [isLoadingTeams, fetchTeams] = useFetch<Array<ITeamBasic>>('/teams')

	React.useEffect(() => {
		if (!open) return
		if (isLoadingTeams) return
		if (teamsList != null) return
		fetchTeams().then(response => setTeamsList(response?.data ?? []), context.handleFetchError)
	}, [open])

	const onDrop = ({ removedIndex, addedIndex }: {
		removedIndex: number | null
		addedIndex: number | null
	}) => {
		if (removedIndex == null) return
		if (addedIndex == null) return

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

	const remove = (removedIndex: number) => {
		let newItems = items.slice()
		newItems.splice(removedIndex, 1)
		setItems(newItems)
	}

	const insert = (option: IFormParticipant | null) => {
		if (option == null) return
		let newItems = items.concat([option])
		setSearchValue('')
		setItems(newItems)
	}

	const teamAsFormParticipant = (team: ITeamBasic | null) => team == null ? null : ({ name: team.name, team: { id: team.id, name: team.name } }) as IFormParticipant

	return <>
		<Dialog open={open} fullWidth disableEnforceFocus>
			<DialogTitle>Event participants</DialogTitle>
			<DialogContent>
				<Autocomplete
					options={teamsList ?? []}
					value={null}
					inputValue={searchValue}
					getOptionLabel={option => option.name || ''}
					getOptionDisabled={option => items.find(i => i.team.id === option.id) != null}
					onInputChange={(_event, text) => setSearchValue(text)}
					onChange={(_event, option) => insert(teamAsFormParticipant(option))}
					blurOnSelect
					fullWidth
					renderInput={params => <TextField {...params} variant='filled' label='Add a team' />}
				/>
				{items.length == 0 ? (<>
					<Box sx={{ marginTop: 2, textAlign: 'center' }}>
						<Typography component='span' color='textSecondary'>
							No participants yet. Add some.
						</Typography>
					</Box>
				</>) : (
					<List>
						{/*
							Package react-smooth-dnd hasn't been fixed, typescript error solutions can be found here:
							https://github.com/kutlugsahin/react-smooth-dnd/issues/93
						*/}
						<DndContainer dragHandleSelector='.drag-handle' onDrop={onDrop} lockAxis='y' style={{ border: '1px solid dimgray' }}>
							{items.map((participant, index) => (
								<Draggable key={participant.team.id}>
									<ListItem>
										<ListItemIcon className='drag-handle'>
											<DragHandleIcon />
										</ListItemIcon>
										<ListItemText>
											{participant.name}
											{participant.team.name !== participant.name ? <>
												{' '}
												<Typography component='span' color='textSecondary'>
													will be renamed to
												</Typography>
												{' '}
												<Typography component='span'>
													{participant.team.name}
												</Typography>
											</> : null}
										</ListItemText>
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
				)}
			</DialogContent>
			<DialogActions>
				<Button onClick={onClose}>Cancel</Button>
				<Button onClick={shuffle}>Shuffle</Button>
				<Button type='submit' onClick={() => onSubmit(items)}>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default ParticipantsForm
