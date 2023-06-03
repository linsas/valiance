import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, InputLabel, MenuItem, Select } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IGameMap, IMatchup } from './MatchupTypes'

function MatchupMapForm({ open, matchup, onSubmit, onClose }: {
	open: boolean,
	matchup: IMatchup,
	onSubmit: (game: Array<number | null>) => void
	onClose: () => void
}) {
	const context = React.useContext(AppContext)

	const [items, setItems] = React.useState<Array<number | null>>([])

	React.useEffect(() => {
		if (!open) return
		setItems(matchup.games.map(g => g.map?.id ?? null))
	}, [open])

	const [mapList, setMapsList] = React.useState<Array<IGameMap>>([])
	const [isLoadingMaps, fetchMaps] = useFetch<Array<IGameMap>>('/maps')

	React.useEffect(() => {
		if (!open) return
		if (isLoadingMaps) return
		fetchMaps().then(response => setMapsList(response?.data ?? []), context.handleFetchError)
	}, [open])

	const changeMap = (selectedIndex: number, changedMap: number | null) => setItems(l => l.map((exisitngMap, index) => index === selectedIndex ? changedMap : exisitngMap))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Matchup maps</DialogTitle>
			<DialogContent>

				{items.map((map, index) =>
					<FormControl key={index} variant='filled' margin='normal' fullWidth>
						<InputLabel id={'label-select-map-' + index}>Map {index + 1}</InputLabel>
						<Select
							labelId={'label-select-map-' + index}
							value={map != null && mapList.map(m => m.id).includes(map) ? map : ''}
							onChange={event => changeMap(index, Number(event.target.value))}
						>
							{mapList.map(m => <MenuItem key={m.id} value={m.id}>{m.name}</MenuItem>)}
						</Select>
					</FormControl>
				)}

			</DialogContent>
			<DialogActions>
				<Button onClick={onClose}>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(items)}>Submit</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default MatchupMapForm
