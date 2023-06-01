import React from 'react'
import { Box, Button, Dialog, DialogActions, DialogContent, DialogTitle, IconButton, List, ListItem, ListItemIcon, ListItemSecondaryAction, ListItemText, TextField, Typography } from '@mui/material'
import { Autocomplete } from '@mui/material'
import DeleteIcon from '@mui/icons-material/Delete'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { ITeamPlayer } from './TeamTypes'
import { IPlayerBasic } from '../Player/PlayerTypes'

function TeamPlayersForm({ open, list, onSubmit, onClose }: {
	open: boolean
	list: Array<ITeamPlayer>
	onSubmit: (list: Array<ITeamPlayer>) => void
	onClose: () => void
}) {
	const context = React.useContext(AppContext)

	const [items, setItems] = React.useState<Array<ITeamPlayer>>([])

	React.useEffect(() => {
		if (!open) return
		setItems(list)
	}, [open])

	const [playersList, setPlayersList] = React.useState<Array<IPlayerBasic> | null>(null)
	const [searchValue, setSearchValue] = React.useState('')
	const [isLoadingPlayers, fetchPlayers] = useFetch<Array<IPlayerBasic>>('/players')

	React.useEffect(() => {
		if (!open) return
		if (isLoadingPlayers) return
		if (playersList != null) return
		fetchPlayers().then(response => setPlayersList(response?.data ?? []), context.handleFetchError)
	}, [open])

	const remove = (removedIndex: number) => {
		let newItems = items.slice()
		newItems.splice(removedIndex, 1)
		setItems(newItems)
	}

	const insert = (option: ITeamPlayer | null) => {
		if (option == null) return
		let newItems = items.concat([option])
		setSearchValue('')
		setItems(newItems)
	}

	return <>
		<Dialog open={open} fullWidth disableEnforceFocus>
			<DialogTitle>Team players</DialogTitle>
			<DialogContent>
				<Autocomplete
					options={playersList ?? []}
					value={null}
					inputValue={searchValue}
					renderOption={(props, option) => <li {...props} key={option.id}>{option.alias}</li>}
					getOptionLabel={option => option.alias ?? ''}
					getOptionDisabled={option => items.find(i => i.id === option.id) != null}
					onInputChange={(_event, text) => setSearchValue(text)}
					onChange={(_event, option) => insert(option)}
					blurOnSelect
					fullWidth
					renderInput={params => <TextField {...params} variant='filled' label='Add a player' />}
				/>
				{items.length == 0 ? (<>
					<Box sx={{ marginTop: 2, textAlign: 'center' }}>
						<Typography component='span' color='textSecondary'>
							This team has no players right now.
						</Typography>
					</Box>
				</>) : (
					<List>
						<Box sx={{ border: '1px solid dimgray' }}>
							{items.map((player, index) => (
								<ListItem key={player.id}>
									<ListItemText>
										{player.alias}
									</ListItemText>
									<ListItemSecondaryAction>
										<IconButton onClick={() => remove(index)}>
											<DeleteIcon />
										</IconButton>
									</ListItemSecondaryAction>
								</ListItem>
							))}
						</Box>
					</List>
				)}
			</DialogContent>
			<DialogActions>
				<Button onClick={onClose}>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(items)}>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default TeamPlayersForm
