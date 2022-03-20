import React from "react";
import { Link } from "react-router-dom";
import Fetch from "view/Fetch";

export default function TypeMenuItem(props: TypeMenuItemProps): JSX.Element {
	return (
		<Fetch input={`/api/types/${props.id}/`}>
			{(resource, data) => (
				<ul>
					{data.map((item: any) => (
						<li key={item.id}>
							<Link to={`/${item.id}/`}>{item.name}</Link>
							<TypeMenuItem id={item.id}/>
						</li>
					))}
				</ul>
			)}
		</Fetch>
	);
}

type TypeMenuItemProps = {
	id: number;
}
