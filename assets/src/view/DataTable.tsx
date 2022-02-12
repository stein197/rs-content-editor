import React from "react";
import {Table} from "react-bootstrap";

export default function DataTable(props: DataTableProps): JSX.Element | null {
	const columnsNames = props.data.length ? Object.keys(props.data[0]) : null;
	return columnsNames && (
		<Table striped bordered hover className="m-0">
			<thead>
				<tr>
					{columnsNames.map(name => (
						<th key={name}>{name}</th>
					))}
				</tr>
			</thead>
			<tbody>
				{props.data.map(item => (
					<tr key={item.id}>
						{columnsNames.map(name => (
							<td key={name}>{item[name]}</td>
						))}
					</tr>
				))}
			</tbody>
		</Table>
	);
}

type DataTableProps = {
	data: any[];
	actions?: ("delete")[];
}
