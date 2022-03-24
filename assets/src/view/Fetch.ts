import React from "react";

export default function Fetch(props: FetchProps): JSX.Element | null {
	const [response, setResponse] = React.useState<Response>();
	const [data, setData] = React.useState<any>();

	React.useEffect((async () => {
		const response = await fetch(props.input, props.init);
		setResponse(response);
		setData(await response.json());
	}) as unknown as () => void, [props.input]);

	return response && data ? props.children(response, data) : null;
}

type FetchProps = {
	children(response: Response, data: any): JSX.Element | null;
	input: Parameters<typeof fetch>[0];
	init?: Parameters<typeof fetch>[1];
}
