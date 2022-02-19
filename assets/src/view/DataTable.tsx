import React from "react";
import {Table, Button} from "react-bootstrap";
import Foreach from "view/flow/Foreach";

// TODO: Replace actions stubs
export default function DataTable(props: DataTableProps): JSX.Element | null {
	const columnsNames = props.data && props.data.length ? Object.keys(props.data[0]) : null;
	const columnActions = props.actions?.filter(action => action !== "create") || [];
	const hasColumnActions = columnActions.length > 0;
	return columnsNames && (
		<Table striped bordered hover className="m-0">
			<thead>
				<tr>
					<Foreach items={columnsNames}>
						{React.useCallback(item => (
							<th key={item}>{item}</th>
						), [])}
					</Foreach>
					{hasColumnActions && (
						<th>Действия</th>
					)}
				</tr>
			</thead>
			<tbody>
				<Foreach items={props.data}>
					{React.useCallback(item => (
						<tr key={item.id}>
							<Foreach items={columnsNames}>
								{React.useCallback(colName => (
									<td key={colName}>{item[colName].toString()}</td>
								), [])}
							</Foreach>
							{hasColumnActions && (
								<td>
									<Foreach items={columnActions}>
										{React.useCallback(action => (
											<a href="#" key={item.id}>{action}</a>
										), [])}
									</Foreach>
								</td>
							)}
						</tr>
					), [])}
				</Foreach>
			</tbody>
			{props.actions?.includes("create") && (
				<tfoot>
					<tr>
						<td colSpan={columnsNames.length + +hasColumnActions}>
							<Button className="w-100">Создать</Button>
						</td>
					</tr>
				</tfoot>
			)}
		</Table>
	);
}

type DataTableProps = {
	data: any[];
	actions?: ("delete" | "create")[];
}
