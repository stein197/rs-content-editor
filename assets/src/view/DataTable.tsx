import React from "react";
import {Table, Button, Modal, Form} from "react-bootstrap";
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
				}).then(() => {
					onModalCloseClick();
					location.reload();
				});
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
						props.props ? (
							<Form>
								<Table>
									<tbody className="edit">
										{props.props.map((prop: any) => (
											<tr key={prop.name}>
												<td>{prop.name}</td>
												<td>
													{Array.isArray(prop.type) ? (
														<select className="form-select" name={prop.name}>
															{prop.type.map((opt: any) => (
																<option value={opt}>{opt}</option>
															))}
														</select>
													) : prop.type === "boolean" || prop.type === "date" ? (
														<input className="form-check-label" name={prop.name} type={prop.type === "boolean" ? "checkbox" : "date"}/>
													) : (
														<Form.Control disabled={prop.name === "id"} type={prop.type === "number" ? "number" : "text"} placeholder={prop.name} name={prop.name}/>
													)}
												</td>
											</tr>
										))}
									</tbody>
								</Table>
							</Form>
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
													<tr key={prop.name}>
														<td>{prop.name}</td>
														<td>
															{prop.type === "boolean" || prop.type === "date" ? (
																<input className="form-check-label" name={prop.name} type={prop.type === "boolean" ? "checkbox" : "date"}/>
															) : (
																<Form.Control disabled={prop.name === "id"} type={prop.type === "number" ? "number" : "text"} placeholder={prop.name} name={prop.name}/>
															)}
														</td>
													</tr>
												))}
											</tbody>
										</Table>
									</Form>
								)}
							</Fetch>
						)
					)}
				</Modal.Body>
				<Modal.Footer>
					<Button variant="primary" onClick={onModalActionClick}>{getActionNameByEnum(modalAction)}</Button>
					<Button variant="danger" onClick={onModalCloseClick}>Отмена</Button>
				</Modal.Footer>
			</Modal>
			<div style={{overflow: "auto"}}>
				<Table striped bordered hover className="m-0">
					<thead>
						<tr>
							{columnsNames.map(item => (
								<th key={typeof item === "object" ? JSON.stringify(item) : item}>{typeof item === "object" ? JSON.stringify(item) : item}</th>
							))}
							{hasColumnActions && (
								<th>Действия</th>
							)}
						</tr>
					</thead>
					{props.propsUrl ? (
						<Fetch input={props.propsUrl}>
							{(propsResponse, propsData) => (
								<tbody>
									{props.data.map(item => (
										<EntityRow key={item.id} propTypes={propsData} crudUrl={props.crudUrl!!} id={item.id} props={item} columns={columnsNames}/>
									))}
								</tbody>
							)}
						</Fetch>
					) : (
						<tbody>
							{props.data.map(item => (
								<EntityRow key={item.id} crudUrl={props.crudUrl!!} id={item.id} props={item} columns={columnsNames}/>
							))}
						</tbody>

					)}
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
			</div>
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
		const input = (tr as HTMLElement).querySelector("input");
		if (input) {
			const inputType = input.getAttribute("type");
			result[input.getAttribute("name")!.toString()] = !inputType || inputType === "text" ? (
				input!.value
			) : inputType === "number" ? (
				+input!.value
			) : inputType === "checkbox" ? (
				input.checked
			) : (
				input!.value
			);
		} else {
			const select = (tr as HTMLElement).querySelector("select")!!;
			result[select.getAttribute("name")!.toString()] = select.value;
		}
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
	props?: {
		name: string;
		type: string | string[];
	}[];
	crudUrl?: string;
}
