import React from "react";
import {ListGroup} from "react-bootstrap";
import Fetch from "view/Fetch";
import Foreach from "view/flow/Foreach";

export default function TypeMenuItem(props: TypeMenuItemProps): JSX.Element {
	return (
		<Fetch input={`/api/types/${props.id}/`}>
			{(resource, data) => (
				<Foreach items={data}>
					{(item: any) => (
						<ListGroup.Item key={item.id}>
							<a href={item.id}>{item.name}</a>
							<TypeMenuItem id={item.id}/>
						</ListGroup.Item>
					)}
				</Foreach>
			)}
		</Fetch>
	);
}

type TypeMenuItemProps = {
	id: number;
}
