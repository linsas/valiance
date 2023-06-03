import React from 'react'
import { Autocomplete, Button, CircularProgress, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, TextField } from '@mui/material'

import AppContext from '../../main/AppContext'
import useFetch from '../../utility/useFetch'
import { IGameMap, IMatchup } from './MatchupTypes'

function MatchupMapForm({ open, matchup, onSubmit, onClose }: {
	open: boolean,
	matchup: IMatchup,
	onSubmit: (game: Array<IGameMap | null>) => void
	onClose: () => void
}) {
	const context = React.useContext(AppContext)

	const [items, setItems] = React.useState<Array<IGameMap | null>>([])

	React.useEffect(() => {
		if (!open) return
		setItems(matchup.games.map(g => g.map ?? null))
	}, [open])

	const [mapList, setMapsList] = React.useState<Array<IGameMap>>([])
	const [isLoadingMaps, fetchMaps] = useFetch<Array<IGameMap>>('/maps')

	React.useEffect(() => {
		if (!open) return
		if (isLoadingMaps) return
		fetchMaps().then(response => setMapsList(response?.data ?? []), context.handleFetchError)
	}, [open])

	const changeMap = (gameIndex: number, chosenMap: IGameMap | null) => setItems(l => l.map((exisitngMap, index) => index === gameIndex ? chosenMap : exisitngMap))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Matchup maps</DialogTitle>
			<DialogContent>

				{items.map((map, index) =>
					<FormControl key={index} variant='filled' margin='normal' fullWidth>
						<Autocomplete
  							renderInput={(params) =>
								<TextField {...params} variant='filled' label={'Map ' + (index + 1)}
									// InputProps={{
									// 	...params.InputProps,
									// 	endAdornment: (
									// 	<React.Fragment>
									// 		{isLoadingMaps ? <CircularProgress color='inherit' size={20} sx={{ alignSelf: 'flex-start' }} /> : null}
									// 		{params.InputProps.endAdornment}
									// 	</React.Fragment>
									// 	),
									// }}
								/>
							}
							options={mapList}
							renderOption={(props, option) => <li {...props} key={option.id}>{option.name}</li>}
							getOptionLabel={option => option.name}
							loading={isLoadingMaps}
							value={map ?? null}
							onChange={(event, option) => changeMap(index, option ?? null)}
						/>
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
