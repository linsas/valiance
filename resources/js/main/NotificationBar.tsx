import React from 'react'
import { Chip, Portal, Snackbar } from '@mui/material'
import { Alert } from '@mui/material'
import { ApplicationError } from './AppContext'

function Notifications({ queue, setQueue }:{
	queue: Array<ApplicationError>
	setQueue: React.Dispatch<React.SetStateAction<ApplicationError[]>>
}) {
	const [desiredOpen, setOpen] = React.useState(false)
	const [message, setMessage] = React.useState<string | null>(null)

	const displayNext = () => {
		if (queue.length === 0) return
		setMessage(queue[0])
		setQueue(queue.slice(1))
		setOpen(true)
	}

	React.useEffect(() => {
		if (message != null) return
		displayNext()
	}, [queue])

	const handleExited = () => {
		setMessage(null)
		displayNext()
	}

	return <Portal>
		<Snackbar open={desiredOpen} TransitionProps={{ onExited: handleExited }} anchorOrigin={{ vertical: 'bottom', horizontal: 'center' }}>
			<Alert severity='error' onClose={() => setOpen(false)}>
				{message} {queue.length > 0 ? <Chip size='small' label={queue.length} /> : null}
			</Alert>
		</Snackbar>
	</Portal>
}

export default Notifications
