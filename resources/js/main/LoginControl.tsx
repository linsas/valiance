import React from 'react'

import AppContext from './AppContext'
import useFetch from '../utility/useFetch'
import LoginForm from './LoginForm'

const tokenCookieName = 'valiance_token'

function LoginControl({ isOpen, setOpen }: {
	isOpen: boolean,
	setOpen: (open: boolean) => void,
}) {
	const context = React.useContext(AppContext)

	const [isLoading, fetchLogin] = useFetch<{ token: string }>('/login', 'POST')

	const parseToken = (token: string) => {
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

	const onLogin = (token: string | null) => {
		if (token == null) return
		setOpen(false)
		const tokenString = token
		const payload = parseToken(tokenString)
		context.setJWT({ raw: tokenString, payload })
		document.cookie = tokenCookieName + '=' + tokenString + ';path=/;samesite=strict;expires=' + new Date(payload.exp * 1000).toUTCString()
	}

	const onSubmit = (credentials: { username: string, password: string }) => {
		fetchLogin(credentials).then(response => onLogin(response.data?.token ?? null), context.handleFetchError)
	}

	return <LoginForm open={isOpen} onClose={() => setOpen(false)} onSubmit={onSubmit} />
}

export default LoginControl
