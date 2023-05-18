import React from 'react'
import { Box, Typography } from '@mui/material'

function Footer() {
	const envObject = process.env

	const [visible, setVisible] = React.useState(false)

	if (envObject == null) return null

	return <>
		<Box sx={{ marginTop: 2, padding: 2, opacity: 0.2 }} component='footer'>
			<Typography
				color='textSecondary'
				variant='body2'
				component='span'
				onMouseEnter={() => setVisible(true)}
				onMouseLeave={() => setVisible(false)}
				sx={{ opacity: visible ? 1 : 0 }}
			>
				{envObject.revision} - {envObject.buildDate}
			</Typography>
		</Box>
	</>
}

export default Footer
