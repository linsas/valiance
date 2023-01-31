import React from 'react'
import { Hidden } from '@mui/material'

import { IMatchup } from '../../../Matchup/MatchupTypes'
import { BracketContainer, BracketMatchup } from './BracketComponents'
import { CompactMatchupsList } from './CompactMatchupsList'

export function BracketSingleElimination4Team({ semifinals1, semifinals2, finals }: {
	semifinals1: IMatchup | null,
	semifinals2: IMatchup | null,
	finals: IMatchup | null,
}) {
	return <>
		<Hidden mdDown>
			<BracketContainer>
				<BracketMatchup area='1/1/2/2' matchup={semifinals1} />
				<BracketMatchup area='2/1/3/2' matchup={semifinals2} />
				<BracketMatchup area='1/2/3/3' matchup={finals} />
			</BracketContainer>
		</Hidden>
		<Hidden mdUp>
			<CompactMatchupsList title='Semifinals' matchups={[semifinals1, semifinals2]} />
			<CompactMatchupsList title='Grand Final' matchups={[finals]} />
		</Hidden>
	</>
}

export function BracketSingleElimination8Team({ quarterfinals1, quarterfinals2, quarterfinals3, quarterfinals4, semifinals1, semifinals2, finals }: {
	quarterfinals1: IMatchup | null,
	quarterfinals2: IMatchup | null,
	quarterfinals3: IMatchup | null,
	quarterfinals4: IMatchup | null,
	semifinals1: IMatchup | null,
	semifinals2: IMatchup | null,
	finals: IMatchup | null,
}) {
	return <>
		<Hidden mdDown>
			<BracketContainer>
				<BracketMatchup area='1/1/2/2' matchup={quarterfinals1} />
				<BracketMatchup area='2/1/3/2' matchup={quarterfinals2} />
				<BracketMatchup area='3/1/4/2' matchup={quarterfinals3} />
				<BracketMatchup area='4/1/5/2' matchup={quarterfinals4} />
				<BracketMatchup area='1/2/3/3' matchup={semifinals1} />
				<BracketMatchup area='3/2/5/3' matchup={semifinals2} />
				<BracketMatchup area='1/3/5/4' matchup={finals} />
			</BracketContainer>
		</Hidden>
		<Hidden mdUp>
			<CompactMatchupsList title='Quarterfinals' matchups={[quarterfinals1, quarterfinals2, quarterfinals3, quarterfinals4]} />
			<CompactMatchupsList title='Semifinals' matchups={[semifinals1, semifinals2]} />
			<CompactMatchupsList title='Grand Final' matchups={[finals]} />
		</Hidden>
	</>
}

export function BracketDoubleElimination4Team({ opening1, opening2, upper, lower, deciding }: {
	opening1: IMatchup | null,
	opening2: IMatchup | null,
	upper: IMatchup | null,
	lower: IMatchup | null,
	deciding: IMatchup | null,
}) {
	return <>
		<Hidden mdDown>
			<BracketContainer>
				<BracketMatchup area='1/1/2/2' matchup={opening1} />
				<BracketMatchup area='2/1/3/2' matchup={opening2} />
				<BracketMatchup area='1/2/3/3' matchup={upper} />
				<BracketMatchup area='3/2/5/3' matchup={lower} />
				<BracketMatchup area='3/3/4/4' matchup={deciding} />
			</BracketContainer>
		</Hidden>
		<Hidden mdUp>
			<CompactMatchupsList title='Opening matches' matchups={[opening1, opening2]} />

			<CompactMatchupsList title='Upper Bracket match' matchups={[upper]} />
			<CompactMatchupsList title='Lower Bracket match' matchups={[lower]} />

			<CompactMatchupsList title='Deciding match' matchups={[deciding]} />
		</Hidden>
	</>
}
