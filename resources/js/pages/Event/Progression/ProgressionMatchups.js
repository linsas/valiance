import React from 'react'

import { BracketSingleElim4Team, BracketSingleElim8Team, BracketDoubleElim4Team } from './ProgressionStages'

function SingleElim4Team({ matchups }) {
	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')

	return <BracketSingleElim4Team
		semifinals1={sf1}
		semifinals2={sf2}
		finals={finals}
	/>
}

function SingleElim8Team({ matchups }) {
	const qf1 = matchups.find(m => m.key === 'qf1')
	const qf2 = matchups.find(m => m.key === 'qf2')
	const qf3 = matchups.find(m => m.key === 'qf3')
	const qf4 = matchups.find(m => m.key === 'qf4')
	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')

	return <BracketSingleElim8Team
		quarterfinals1={qf1}
		quarterfinals2={qf2}
		quarterfinals3={qf3}
		quarterfinals4={qf4}
		semifinals1={sf1}
		semifinals2={sf2}
		finals={finals}
	/>
}

function Minor8Team({ matchups }) {
	const a1 = matchups.filter(m => m.key === 'ao')[0]
	const a2 = matchups.filter(m => m.key === 'ao')[1]
	const aw = matchups.find(m => m.key === 'aw')
	const al = matchups.find(m => m.key === 'al')

	const b1 = matchups.filter(m => m.key === 'bo')[0]
	const b2 = matchups.filter(m => m.key === 'bo')[1]
	const bw = matchups.find(m => m.key === 'bw')
	const bl = matchups.find(m => m.key === 'bl')

	const ad = matchups.find(m => m.key === 'ad')
	const bd = matchups.find(m => m.key === 'bd')

	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')
	return <>
		<Separator title='Group A' />
		<BracketDoubleElim4Team
			opening1={a1}
			opening2={a2}
			upper={aw}
			lower={al}
			deciding={ad}
		/>
		<Separator title='Group B' />
		<BracketDoubleElim4Team
			opening1={b1}
			opening2={b2}
			upper={bw}
			lower={bl}
			deciding={bd}
		/>
		<Separator title='Playoffs' />
		<BracketSingleElim4Team
			semifinals1={sf1}
			semifinals2={sf2}
			finals={finals}
		/>
	</>
}

function Minor16Team({ matchups }) {
	const a1 = matchups.filter(m => m.key === 'ao')[0]
	const a2 = matchups.filter(m => m.key === 'ao')[1]
	const aw = matchups.find(m => m.key === 'aw')
	const al = matchups.find(m => m.key === 'al')
	const ad = matchups.find(m => m.key === 'ad')

	const b1 = matchups.filter(m => m.key === 'bo')[0]
	const b2 = matchups.filter(m => m.key === 'bo')[1]
	const bw = matchups.find(m => m.key === 'bw')
	const bl = matchups.find(m => m.key === 'bl')
	const bd = matchups.find(m => m.key === 'bd')

	const c1 = matchups.filter(m => m.key === 'co')[0]
	const c2 = matchups.filter(m => m.key === 'co')[1]
	const cw = matchups.find(m => m.key === 'cw')
	const cl = matchups.find(m => m.key === 'cl')
	const cd = matchups.find(m => m.key === 'cd')

	const d1 = matchups.filter(m => m.key === 'do')[0]
	const d2 = matchups.filter(m => m.key === 'do')[1]
	const dw = matchups.find(m => m.key === 'dw')
	const dl = matchups.find(m => m.key === 'dl')
	const dd = matchups.find(m => m.key === 'dd')

	const qf1 = matchups.find(m => m.key === 'qf1')
	const qf2 = matchups.find(m => m.key === 'qf2')
	const qf3 = matchups.find(m => m.key === 'qf3')
	const qf4 = matchups.find(m => m.key === 'qf4')
	const sf1 = matchups.find(m => m.key === 'sf1')
	const sf2 = matchups.find(m => m.key === 'sf2')
	const finals = matchups.find(m => m.key === 'f')
	return <>
		<Separator title='Group A' />
		<BracketDoubleElim4Team
			opening1={a1}
			opening2={a2}
			upper={aw}
			lower={al}
			deciding={ad}
		/>
		<Separator title='Group B' />
		<BracketDoubleElim4Team
			opening1={b1}
			opening2={b2}
			upper={bw}
			lower={bl}
			deciding={bd}
		/>
		<Separator title='Group C' />
		<BracketDoubleElim4Team
			opening1={c1}
			opening2={c2}
			upper={cw}
			lower={cl}
			deciding={cd}
		/>
		<Separator title='Group D' />
		<BracketDoubleElim4Team
			opening1={d1}
			opening2={d2}
			upper={dw}
			lower={dl}
			deciding={dd}
		/>
		<Separator title='Playoffs' />
		<BracketSingleElim8Team
			quarterfinals1={qf1}
			quarterfinals2={qf2}
			quarterfinals3={qf3}
			quarterfinals4={qf4}
			semifinals1={sf1}
			semifinals2={sf2}
			finals={finals}
		/>
	</>
}

function ProgressionMatchups({ event }) {
	const matchups = event.matchups

	if (event.format === 1) {
		return <SingleElim4Team matchups={matchups} />
	} else if (event.format === 2) {
		return <SingleElim8Team matchups={matchups} />
	} else if (event.format === 3) {
		return <Minor8Team matchups={matchups} />
	} else if (event.format === 4) {
		return <Minor16Team matchups={matchups} />
	}
	return null
}

export default ProgressionMatchups
