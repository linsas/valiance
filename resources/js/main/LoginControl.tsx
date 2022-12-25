import React from 'react'

import AppContext from './AppContext'
import useFetch from '../utility/useFetch'
import LoginForm from './LoginForm'

const tokenCookieName = 'valiance_token'

function LoginControl({ isOpen, setOpen }) {
	const context = React.useContext(AppContext)

	const [isLoading, fetchLogin] = useFetch('/api/login', 'POST')

	const parseToken = (token) => {
		const payloadBase64 = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/')
		const payload = JSON.parse(window.atob(payloadBase64))
		return payload
	}

	React.useEffect(() => {
		const cookieValue = document.cookie.split('; ').find((row) => row.startsWith(tokenCookieName + '='))?.split('=')[1]
		if (cookieValue == null) return
		const payload = parseToken(cookieValue)
		context.setJWT({ raw: cookieValue, payload })
	}, [])

	const onResponse = (response) => {
		setOpen(false)
		const tokenString = response.json.token
		const payload = parseToken(tokenString)
		context.setJWT({ raw: tokenString, payload })
		document.cookie = tokenCookieName + '=' + tokenString + ';path=/;samesite=strict;expires=' + new Date(payload.exp * 1000).toUTCString()
	}

	const onSubmit = (credentials) => {
		fetchLogin(credentials).then(onResponse, context.notifyFetchError)
	}

	return <LoginForm open={isOpen} onClose={() => setOpen(false)} onSubmit={onSubmit} />
}

export default LoginControl
