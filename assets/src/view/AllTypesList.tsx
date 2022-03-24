import React from "react";
import Fetch from "view/Fetch";

export default function AllTypesList(props: {id?: number, depth?: number, children(id: number, name: string, depth: number): JSX.Element | string}): JSX.Element {
	return (
		<Fetch input={`/api/types/${props.id ?? 0}/`}>
			{(response, data) => (
				data.length > 0 ? (
					<>
						{data.map((item: any) => (
							<>
								{props.children(item.id, item.name, props.depth ?? 0)}
								<AllTypesList id={item.id} depth={(props.depth ?? 0) + 1}>{props.children}</AllTypesList>
							</>
						))}
					</>
				) : (
					null
				)
			)}
		</Fetch>
	)
}
