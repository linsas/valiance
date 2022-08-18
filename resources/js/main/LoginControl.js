import React from 'react'

import AppContext from '../main/AppContext'
import useFetch from '../utility/useFetch'
import LoginForm from './LoginForm'

function LoginControl({ isOpen, setOpen }) {
	const context = React.useContext(AppContext)

	const [isLoading, fetchLogin] = useFetch('/api/login', 'POST')

	const parseToken = (token) => {
		const payloadBase64 = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/')
		const payload = JSON.parse(window.atob(payloadBase64))
		return payload
	}

	const onResponse = (response) => {
		setOpen(false)
		const tokenString = response.json.token
		const payload = parseToken(tokenString)
		context.setJWT({ raw: tokenString, payload })
	}

	const onSubmit = (credentials) => {
		fetchLogin(credentials).then(onResponse, console.error)
	}

	return <LoginForm open={isOpen} onClose={() => setOpen(false)} onSubmit={onSubmit} />
}

export default LoginControl
