export default function errorAsText(error) {
	if (error.name !== 'ResponseNotOkError')
		return error.message

	if (error.result.json != null && error.result.json.message != null)
		return error.result.json.message
	return error.result.status + ' ' + error.result.statusText
}
