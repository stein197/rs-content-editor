import React from "react";
import {Table, Button, Modal, Form} from "react-bootstrap";
import Foreach from "view/flow/Foreach";
import Fetch from "view/Fetch";

// TODO: Replace actions stubs
export default function DataTable(props: DataTableProps): JSX.Element | null {
	const columnsNames = props.data && props.data.length ? Object.keys(props.data[0]) : null;
	const columnActions = props.actions?.filter(action => action !== "create") || [];
	const hasColumnActions = columnActions.length > 0;
	const [modalAction, setModalAction] = React.useState(Action.Create);
	const [modalVisible, setModalVisible] = React.useState(false);

	const onModalCloseClick = React.useCallback(() => {
		setModalVisible(false);
		// clearData();
	}, []);
	// TODO
	const onDeleteClick = React.useCallback((e: React.SyntheticEvent<HTMLAnchorElement, MouseEvent>) => {
		e.preventDefault();
		setModalAction(Action.Delete);
		setModalVisible(true);
	}, []);
	// TODO
	const onCreateClick = React.useCallback((e) => {
		e.preventDefault();
		setModalAction(Action.Create);
		setModalVisible(true);
	}, []);
	// TODO
	const onEditClick = React.useCallback((e: React.SyntheticEvent<HTMLAnchorElement, MouseEvent>) => {
		e.preventDefault();
		setModalAction(Action.Update);
		setModalVisible(true);
	}, []);
	// TODO
	const onModalActionClick = React.useCallback(e => {
		
		onModalCloseClick();
	}, []);
	
	return columnsNames && (
		<>
			<Modal show={modalVisible} onHide={onModalCloseClick}>
				<Modal.Header closeButton>
					<Modal.Title>{getActionNameByEnum(modalAction)}</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					{modalAction === Action.Delete ? (
						<p>Вы уверены что хотите удалить сущность</p>
					) : (
						<Fetch input={props.propsUrl}>
							{(response, data) => (
								<Form>
									<Table>
										<tbody>
											<tr>
												<td>id</td>
												<td>
													<Form.Control placeholder="id"/>
												</td>
											</tr>
											{data.map((prop: any) => (
												<tr>
													<td>{prop.name}</td>
													<td>
														<Form.Control/>
													</td>
												</tr>
											))}
										</tbody>
									</Table>
								</Form>
							)}
						</Fetch>
					)}
				</Modal.Body>
				<Modal.Footer>
					<Button variant="primary" onClick={onModalActionClick}>{getActionNameByEnum(modalAction)}</Button>
					<Button variant="danger" onClick={onModalCloseClick}>Отмена</Button>
				</Modal.Footer>
			</Modal>
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
												<>
													<a href="" key={item.id} onClick={action == "delete" ? onDeleteClick : (action === "edit" ? onEditClick : undefined)}>{action}</a>
													<br/>
												</>
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
								<button type="button" className="w-100 btn btn-primary" onClick={onCreateClick}>Создать</button>
							</td>
						</tr>
					</tfoot>
				)}
			</Table>
		</>
	);
}

function getActionNameByEnum(action: Action): string {
	switch (action) {
		case Action.Create:
			return "Создать";
		case Action.Read:
			return "Читать";
		case Action.Update:
			return "Обновить";
		case Action.Delete:
			return "Удалить";
	}
}

enum Action {
	Create,
	Read,
	Update,
	Delete
}

type DataTableProps = {
	data: any[];
	actions?: ("delete" | "create" | "edit")[];
	propsUrl: string;
}
