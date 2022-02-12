import React from "react";

export default function Foreach<T>(props: ForeachProps<T>): JSX.Element {
	return (
		<>
			{props.items.map(props.children)}
		</>
	);
}

type ForeachProps<T> = {
	items: T[];
	children(value: T, index: number): JSX.Element;
}
