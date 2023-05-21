import React from 'react'
import { Chip, Portal, Snackbar, Typography } from '@mui/material'
import { Alert, AlertTitle } from '@mui/material'

import { ApplicationError } from './AppTypes'

function Notifications({ queue, setQueue }:{
	queue: Array<ApplicationError>
	setQueue: React.Dispatch<React.SetStateAction<ApplicationError[]>>
}) {
	const [desiredOpen, setOpen] = React.useState(false)
	const [item, setItem] = React.useState<ApplicationError | null>(null)

	const displayNext = () => {
		if (queue.length === 0) return
		setItem(queue[0])
		setQueue(queue.slice(1))
		setOpen(true)
	}

	React.useEffect(() => {
		if (item != null) return
		displayNext()
	}, [queue])

	const handleExited = () => {
		setItem(null)
		displayNext()
	}

	return <Portal>
		<Snackbar open={desiredOpen} TransitionProps={{ onExited: handleExited }} anchorOrigin={{ vertical: 'bottom', horizontal: 'center' }}>
			<Alert severity='error' onClose={() => setOpen(false)}>
				{item == null ? null : <>
					<AlertTitle>
						{item.title}
						{queue.length === 0 ? null : <>
							{' '}
							<Chip size='small' label={queue.length + ' more'} />
						</>}
					</AlertTitle>
					<Typography>{item.message}</Typography>
				</>}
			</Alert>
		</Snackbar>
	</Portal>
}

export default Notifications
