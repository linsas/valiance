import React from 'react'

import AppContext from '../main/AppContext'
import { ApplicationError } from '../main/AppTypes';

export interface FetchResult<T> {
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

const apiEndpointPrefix = '/api'

function never<T>(): Promise<FetchResult<T>> {
	return new Promise(() => { })
}

export default function useFetch<T>(apiEndpoint: string, method: HttpMethod = 'GET'): [
	boolean,
	(data?: unknown) => Promise<FetchResult<T>>
] {
	const [isLoading, setIsLoading] = React.useState(false)

	const context = React.useContext(AppContext)

	const url = apiEndpointPrefix + apiEndpoint

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

			if (response.headers.get('Content-Type') !== 'application/json') {
				throw { title: 'Bad Response', message: 'The server did not return the correct content type.', result } as FetchError<T>
			}

			result.json = await response.json()

			if (!isMountedRef.current) return never<T>()
			setIsLoading(false)

			if (!response.ok){
				let message = 'The server did not return a 2xx response.'

				if (result.json != null && typeof result.json === 'object' && 'message' in result.json)
					message = String(result.json.message)

				throw { title: result.status + ' ' + result.statusText, message, result } as FetchError<T>
			}

			return result
		}).catch((error: unknown) => {
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
