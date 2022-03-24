import React from "react";
import {Link, useParams} from "react-router-dom";
import Fetch from "view/Fetch";

export default function TypeMenuItem(props: TypeMenuItemProps): JSX.Element {
	const params = useParams();
	return (
		<Fetch input={`/api/types/${props.id}/`}>
			{(resource, data) => (
				data.length > 0 ? (
					<ul>
						{data.map((item: any) => (
							<li key={item.id}>
								<Link to={`/${item.id}/`} className={params.typeID == item.id ? "active" : ""}>{item.name}</Link>
								<TypeMenuItem id={item.id}/>
							</li>
						))}
					</ul>
				) : (
					null
				)
			)}
		</Fetch>
	);
}

type TypeMenuItemProps = {
	id: number;
}
