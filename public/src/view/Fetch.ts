import React from "react";

export default function Fetch(props: FetchProps): JSX.Element | null {
	const [response, setResponse] = React.useState<Response>();
	const [json, setJson] = React.useState<any>();

	React.useEffect((async () => {
		const response = await fetch(props.input, props.init);
		setResponse(response);
		if (props.json)
			setJson(await response.json());
	}) as unknown as () => void, [props.input]);

	return response && (!props.json || json) ? props.children(response, json) : null;
}

type FetchProps = {
	json: boolean;
	children(response: Response, json?: any): JSX.Element;
	input: Parameters<typeof fetch>[0];
	init?: Parameters<typeof fetch>[1];
}
