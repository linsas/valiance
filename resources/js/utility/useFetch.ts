import React from 'react'

import AppContext from '../main/AppContext'

const never = new Promise(() => { })

export default function useFetch(url, method = 'GET') : [boolean, (data?: any) => Promise<any>] {
	const [isLoading, setIsLoading] = React.useState(false)

	const context = React.useContext(AppContext)

	const controllerRef = React.useRef(new AbortController())
	const isMountedRef = React.useRef(true)

	const fetchData = React.useCallback((data) => {
		controllerRef.current.abort()
		controllerRef.current = new AbortController()
		setIsLoading(true)

		// console.log(window.location.host + url)

		const headers = {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		}
		if (context.jwt != null) headers['Authorization'] = 'JWT ' + context.jwt.raw

		return fetch(url, {
			signal: controllerRef.current.signal,
			method,
			headers,
			body: JSON.stringify(data),
		}).then(async response => {
			const result = {
				headers: response.headers, // Headers object
				status: response.status,
				statusText: response.statusText,
				url: response.url,
				json: null,
			}

			if (response.headers.get('Content-Type') === 'application/json')
				result.json = await response.json()

			if (!isMountedRef.current) return never
			setIsLoading(false)

			if (!response.ok) throw { name: 'ResponseNotOkError', message: 'The server did not return a 2xx response. ', result }

			return result
		}).catch((error) => {
			if (!isMountedRef.current) return never
			setIsLoading(false)
			return Promise.reject(error)
		})
	}, [url, method, context.jwt])

	React.useEffect(() => {
		isMountedRef.current = true
		return () => {
			controllerRef.current.abort()
			isMountedRef.current = false
		}
	}, [url, method])

	return [isLoading, fetchData,]
}
