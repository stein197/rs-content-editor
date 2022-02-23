import React from "react";
import {Table, Button, Modal, Form} from "react-bootstrap";
import Foreach from "view/flow/Foreach";
import Fetch from "view/Fetch";
import EntityRow from "view/EntityRow";

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
	const onCreateClick = React.useCallback((e) => {
		e.preventDefault();
		setModalAction(Action.Create);
		setModalVisible(true);
	}, []);
	// TODO
	const onModalActionClick = React.useCallback(e => {
		switch (modalAction) {
			// TODO
			case Action.Create:
				const data = getModalData();
				fetch(props.crudUrl!!, {
					method: "POST",
					body: JSON.stringify(data)
				}).then(() => onModalCloseClick());
				break;
			// TODO
			case Action.Read:
				break;
			// TODO
			case Action.Update:
				break;
			// TODO
			case Action.Delete:
				break;
		}
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
										<tbody className="edit">
											<tr>
												<td>id</td>
												<td>
													<Form.Control placeholder="id" name="id"/>
												</td>
											</tr>
											{data.map((prop: any) => (
												<tr>
													<td>{prop.name}</td>
													<td>
														<Form.Control placeholder={prop.name} name={prop.name}/>
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
								<th key={typeof item === "object" ? JSON.stringify(item) : item}>{typeof item === "object" ? JSON.stringify(item) : item}</th>
							), [])}
						</Foreach>
						{hasColumnActions && (
							<th>Действия</th>
						)}
					</tr>
				</thead>
				<tbody>
					{props.data.map(item => (
						<EntityRow crudUrl={props.crudUrl!!} id={item.id} props={item} columns={columnsNames}/>
					))}
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

function getModalData(): object {
	const result: any = {};
	for (const tr of Array.from(document.body.querySelector(".modal-dialog tbody.edit")!.children)) {
		result[tr.querySelector("input")!.getAttribute("name")!.toString()] = tr.querySelector("input")!.value;
	}
	return result;
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
	crudUrl?: string;
}
