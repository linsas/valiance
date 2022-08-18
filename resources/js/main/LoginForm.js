import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, TextField, FormControl, InputLabel, FilledInput, InputAdornment, IconButton } from '@material-ui/core'
import Visibility from '@material-ui/icons/Visibility'
import VisibilityOff from '@material-ui/icons/VisibilityOff'

function LoginForm({ open, onSubmit, onClose }) {
	const [credentials, setCredentials] = React.useState({ username: '', password: '' })
	const [showPassword, setShowPassword] = React.useState(false)

	const changeUsername = username => setCredentials(p => ({ ...p, username: username }))
	const changePassword = password => setCredentials(p => ({ ...p, password: password }))

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
						// value={credentials.password}
						onChange={event => changePassword(event.target.value)}
						fullWidth
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
				<Button onClick={onClose} color='primary'>Cancel</Button>
				<Button type='submit' onClick={() => onSubmit(credentials)} color='primary'>Login</Button>
			</DialogActions>
		</Dialog>
	</>
}

export default LoginForm
