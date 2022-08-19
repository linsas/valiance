import React from 'react'
import { Chip, Portal, Snackbar } from '@material-ui/core'
import { Alert } from '@material-ui/lab'

function Notifications({ queue, setQueue }) {
	const [desiredOpen, setOpen] = React.useState(false)
	const [message, setMessage] = React.useState(null)

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
		<Snackbar open={desiredOpen} TransitionProps={{ onExited: handleExited }}>
			<Alert severity='error' onClose={() => setOpen(false)}>
				{message} {queue.length > 0 ? <Chip size='small' label={queue.length} /> : null}
			</Alert>
		</Snackbar>
	</Portal>
}

export default Notifications
