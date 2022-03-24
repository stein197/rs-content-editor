import React from "react";
import Fetch from "view/Fetch";

export default function EntityTypeValue(props: EntityTypeValueProps): JSX.Element {
	const [typeID, entityID] = props.value?.split(":").map(id => +id) || [0, 0];
	const [entityArray, setEntityArray] = React.useState<any[]>([]);
	async function onChange(e: React.SyntheticEvent<HTMLSelectElement>) {
		const typeID: string = (e.nativeEvent.target as any).value;
		const response = await fetch(`/api/type/${typeID}/entities/`);
		const data = await response.json();
		setEntityArray(data);
	}
	
	return (
		<div className="d-flex">
			<select name={`${props.name}[type]`} onChange={onChange} className="form-select mx-1 flex-grow-1" defaultValue={typeID}>
				<TypeDropdownOptions/>
			</select>
			<select name={`${props.name}[entity]`} className="form-select mx-1 flex-grow-1" defaultValue={entityID}>
				{entityArray.map(item => (
					<option key={item.id} value={item.id}>{item.id}</option>
				))}
			</select>
		</div>
	);
}

function TypeDropdownOptions(props: {id?: number; depth?: number}): JSX.Element {
	const id = props.id ?? 0;
	const depth = props.depth ?? 0;
	return (
		<Fetch input={`/api/types/${id}/`}>
			{(typesResponse, typesData) => (
				typesData.length > 0 ? (
					<>
						{typesData.map((item: any) => (
							<>
								<option value={item.id}>{"-".repeat(depth) + item.name}</option>
								<TypeDropdownOptions id={item.id} depth={depth + 1}/>
							</>
						))}
					</>
				) : (
					null
				)
			)}
		</Fetch>
	);
}

type EntityTypeValueProps = {
	name: string;
	value?: string;
}