import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, TextField, FormControl, InputLabel, FilledInput, InputAdornment, IconButton } from '@mui/material'
import Visibility from '@mui/icons-material/Visibility'
import VisibilityOff from '@mui/icons-material/VisibilityOff'

function LoginForm({ open, onSubmit, onClose }:{
	open: boolean,
	onSubmit: (credentials: { username: string, password: string }) => void,
	onClose: () => void,
}) {
	const [credentials, setCredentials] = React.useState({ username: '', password: '' })
	const [showPassword, setShowPassword] = React.useState(false)

	const changeUsername = (username: string) => setCredentials(p => ({ ...p, username: username }))
	const changePassword = (password: string) => setCredentials(p => ({ ...p, password: password }))

	return <>
		<Dialog open={open} fullWidth>
			<DialogTitle>Login</DialogTitle>
			<DialogContent>
				<TextField
					autoFocus
					variant='filled'
					margin='normal'
					label='Username'
					type='text'
					value={credentials.username}
					onChange={event => changeUsername(event.target.value)}
					fullWidth
				/>

				<FormControl variant='filled' margin='normal' fullWidth>
					<InputLabel htmlFor='login-password'>Password</InputLabel>
					<FilledInput
						id='login-password'
						type={showPassword ? 'text' : 'password'}
						onChange={event => changePassword(event.target.value)}
						endAdornment={
							<InputAdornment position='end'>
								<IconButton
									onClick={() => setShowPassword(!showPassword)}
									onMouseDown={(event) => event.preventDefault()}
									edge='end'
								>
									{showPassword ? <VisibilityOff /> : <Visibility />}
								</IconButton>
							</InputAdornment>
						}
					/>
				</FormControl>

			</DialogContent>
			<DialogActions>
				<Button onClick={onClose}>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(credentials)}>Login</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default LoginForm
