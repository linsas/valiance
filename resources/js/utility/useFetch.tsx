import React from 'react'

import AppContext from '../main/AppContext'
import { ApplicationError } from '../main/AppTypes';

interface FetchResult<T> {
	headers: Headers;
	status: number;
	statusText: string;
	url: string;
	json: T | null;
}

interface FetchError<T> extends ApplicationError {
	result: FetchResult<T>;
}

type HttpMethod = 'GET' | 'POST' | 'PUT' | 'DELETE'

function never<T>(): Promise<FetchResult<T>> {
	return new Promise(() => { })
}

export function isFetchError(error: ApplicationError): error is FetchError<unknown> {
	return error.name === 'ResponseNotOkError'
}

export function fetchErrorMessageOrNull(error: FetchError<unknown>) {
	if (error.result.json != null && typeof error.result.json === 'object' && 'message' in error.result.json)
		return String(error.result.json.message)
	return null
}

export default function useFetch<T>(url: string, method: HttpMethod = 'GET'): [
	boolean,
	(data?: unknown) => Promise<FetchResult<T>>
] {
	const [isLoading, setIsLoading] = React.useState(false)

	const context = React.useContext(AppContext)

	const controllerRef = React.useRef(new AbortController())
	const isMountedRef = React.useRef(true)

	const fetchData = React.useCallback((data: any) => {
		controllerRef.current.abort()
		controllerRef.current = new AbortController()
		setIsLoading(true)

		// console.log(window.location.host + url)

		const headers: HeadersInit = {
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
			const result: FetchResult<T> = {
				headers: response.headers,
				status: response.status,
				statusText: response.statusText,
				url: response.url,
				json: null,
			}

			if (response.headers.get('Content-Type') === 'application/json')
				result.json = await response.json()

			if (!isMountedRef.current) return never<T>()
			setIsLoading(false)

			if (!response.ok) throw { name: 'ResponseNotOkError', message: 'The server did not return a 2xx response. ', result } as FetchError<T>

			return result
		}).catch((error: FetchError<T>) => {
			if (!isMountedRef.current) return never<T>()
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
