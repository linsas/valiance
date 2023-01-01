import React from 'react';
import { Box, Typography } from '@mui/material';

export function StageSeparator({ title }) {
	return <Box my={2} textAlign='center'>
		<Typography component='span' variant='overline' style={{ padding: '8px 32px', borderLeft: '2px solid grey', borderRight: '2px solid grey' }}>{title}</Typography>
	</Box>
}
