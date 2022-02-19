import React from "react";
import { Link } from "react-router-dom";
import Fetch from "view/Fetch";
import Foreach from "view/flow/Foreach";

export default function TypeMenuItem(props: TypeMenuItemProps): JSX.Element {
	return (
		<Fetch input={`/api/types/${props.id}/`}>
			{(resource, data) => (
				<ul>
					<Foreach items={data}>
						{(item: any) => (
							<li key={item.id}>
								<Link to={`/${item.id}/`}>{item.name}</Link>
								<TypeMenuItem id={item.id}/>
							</li>
						)}
					</Foreach>
				</ul>
			)}
		</Fetch>
	);
}

type TypeMenuItemProps = {
	id: number;
}
