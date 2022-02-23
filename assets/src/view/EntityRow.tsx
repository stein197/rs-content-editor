import React from "react";
import {Table, Button, Modal, Form} from "react-bootstrap";

export default function EntityRow(props: EntityRowProps) {
	const [modalVisible, setModalVisible] = React.useState(false);
	const [deleted, setDeleted] = React.useState(false);
	const [action, setAction] = React.useState(Action.None);
	function onDelete(e: any) {
		e.preventDefault();
		setAction(Action.Delete);
		setModalVisible(true);
	}
	function onEdit(e: any) {
		e.preventDefault();
		setAction(Action.Edit);
		setModalVisible(true);
	}
	const onModalActionClick = React.useCallback(() => {
		if (action === Action.Delete) {
			fetch(props.crudUrl + `${props.id}/`, {
				method: "DELETE"
			}).then(response => {
				if (response.ok) {
					setModalVisible(false);
					setDeleted(true);
				}
			});
		// TODO
		} else if (action === Action.Edit) {

		}
	}, [deleted, action]);
	const onModalCloseClick = React.useCallback(() => {
		setModalVisible(false);
	}, []);
	return deleted ? null : (
		<>
			<Modal show={modalVisible} onHide={onModalCloseClick}>
				<Modal.Header closeButton>
					<Modal.Title>{action === Action.Delete ? "Удалить" : "Редактирование"}</Modal.Title>
				</Modal.Header>
				<Modal.Body></Modal.Body>
				<Modal.Footer>
					<Button variant="primary" onClick={onModalActionClick}>{action === Action.Delete ? "Удалить" : "Сохранить"}</Button>
					<Button variant="danger" onClick={onModalCloseClick}>Отмена</Button>
				</Modal.Footer>
			</Modal>
			<tr>
				{props.columns.map(col => (
					<td key={col}>{props.props[col]}</td>
				))}
				<td key="edit">
					<a href="" onClick={onDelete}>Delete</a>
					<br/>
					<a href="" onClick={onEdit}>Edit</a>
				</td>
			</tr>
		</>
	);
}

enum Action {
	None,
	Delete,
	Edit
}

type EntityRowProps = {
	id: number | string;
	columns: string[];
	props: any;
	crudUrl: string;
}
