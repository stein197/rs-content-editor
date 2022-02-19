import React from "react";
import {Card} from "react-bootstrap";

export default function Content(props: ContentProps): JSX.Element {
	return (
		<Card className="h-100">
			<Card.Body>{props.children}</Card.Body>
		</Card>
	);
}

type ContentProps = {
	children?: any;
}
