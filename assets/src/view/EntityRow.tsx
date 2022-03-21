import React from "react";
import {Table, Button, Modal, Form} from "react-bootstrap";

export default function EntityRow(props: EntityRowProps) {
	const [eProps, setEProps] = React.useState<any>(props.props);
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
	const onModalActionClick = () => {
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
			if (ggRef) {
				const result: any = {};
				for (const tr of Array.from(ggRef.querySelector("tbody").children)) {
					result[(tr as any).querySelector("input")!.getAttribute("name")!.toString()] = (tr as any).querySelector("input")!.value;
				}
				fetch(props.crudUrl + `${props.id}/`, {
					method: "PUT",
					body: JSON.stringify(result)
				}).then(response => {
					if (response.ok) {
						setEProps(result);
					}
					onModalCloseClick();
				})
			}
		}
	};
	const onModalCloseClick = React.useCallback(() => {
		setModalVisible(false);
	}, []);
	let ggRef: any;
	return deleted ? null : (
		<>
			<Modal show={modalVisible} onHide={onModalCloseClick}>
				<Modal.Header closeButton>
					<Modal.Title>{action === Action.Delete ? "Удалить" : "Редактирование"}</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					{action === Action.Edit && (
						<form action="" ref={ref => ggRef = ref}>
							<table className="table">
								<tbody>
									{Object.entries(props.props).map((prop: any) => (
										<tr key={prop[0]}>
											<td>{prop[0]}</td>
											<td>
												<Form.Control placeholder={prop[0]} name={prop[0]} defaultValue={typeof prop[1] === "object" ? JSON.stringify(prop[1]) : prop[1]}/>
											</td>
										</tr>
									))}
								</tbody>
							</table>
						</form>
					)}
				</Modal.Body>
				<Modal.Footer>
					<Button variant="primary" onClick={onModalActionClick}>{action === Action.Delete ? "Удалить" : "Сохранить"}</Button>
					<Button variant="danger" onClick={onModalCloseClick}>Отмена</Button>
				</Modal.Footer>
			</Modal>
			<tr>
				{props.columns.map(col => (
					<td key={col}>{typeof eProps[col] === "object" ? JSON.stringify(eProps[col]) : eProps[col]}</td>
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
