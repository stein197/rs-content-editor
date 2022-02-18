import React from "react";
import {Card} from "react-bootstrap";
import TypeMenuItem from "view/TypeMenuItem";

export default function Sidebar(): JSX.Element {
	return (
		<aside className="h-100">
			<Card>
				<Card.Body>
					<TypeMenuItem id={0}/>
				</Card.Body>
			</Card>
		</aside>
	);
}
