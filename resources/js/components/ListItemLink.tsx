import React from 'react'
import { Link as RouterLink } from 'react-router-dom'
import { ListItem, ListItemButton } from '@mui/material'
import ChevronRightIcon from '@mui/icons-material/ChevronRight'

function ListItemLink({ children, to, dense = false, noChevron = false }: {
	children: React.ReactNode,
	to: string,
	dense?: boolean,
	noChevron?: boolean,
}) {
	return <ListItem dense={dense} disablePadding>
		<ListItemButton component={RouterLink} to={to}>
			{children}
			{!noChevron && <ChevronRightIcon />}
		</ListItemButton>
	</ListItem>
}

export default ListItemLink
