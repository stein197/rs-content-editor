import React from "react";
import {Card, Button, Form, Modal} from "react-bootstrap";
import TypeMenuItem from "view/TypeMenuItem";
import AllTypesList from "view/AllTypesList";

export default function Sidebar(): JSX.Element {
	const [modalVisible, setModalVisible] = React.useState(false);
	const onModalCloseClick = React.useCallback(() => {
		setModalVisible(false);
	}, []);
	const onModalActionClick = () => {
		if (!formRef)
			return;
		const result: any = {};
		for (const tr of Array.from(formRef.querySelector("tbody")!.children)) {
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
				const selectList = (tr as HTMLElement).querySelectorAll("select");
				for (const select of selectList)
					result[select.getAttribute("name")!.toString()] = select.value;
			}
		}
		fetch(`/api/type/`, {
			method: "POST",
			body: JSON.stringify(result)
		}).then(() => location.reload());
	};
	let formRef: HTMLFormElement | null;

	return (
		<>
			<Modal show={modalVisible} onHide={onModalCloseClick}>
				<Modal.Header closeButton>
					<Modal.Title>Создать</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					<form action="" ref={ref => formRef = ref}>
						<table className="table">
							<tbody>
								<tr>
									<td>Имя</td>
									<td>
										<input type="text" className="form-control" name="name"/>
									</td>
								</tr>
								<tr>
									<td>Родитель</td>
									<td>
										<select name="parent" className="form-select">
											<AllTypesList>
												{(id, name, depth) => (
													<option value={id}>{"-".repeat(depth) + name}</option>
												)}
											</AllTypesList>
										</select>
									</td>
								</tr>
								<tr>
									<td>Хранить в родителе</td>
									<td>
										<input type="checkbox" className="form-check-input" name="store_in_parent"/>
									</td>
								</tr>
								<tr>
									<td>Инкремент с</td>
									<td>
										<input type="number" className="form-control" name="increment_from"/>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</Modal.Body>
				<Modal.Footer>
					<Button variant="primary" onClick={onModalActionClick}>Создать</Button>
					<Button variant="danger" onClick={onModalCloseClick}>Отмена</Button>
				</Modal.Footer>
			</Modal>
			<aside className="h-100">
				<Card>
					<Card.Body>
						<TypeMenuItem id={0}/>
						<div className="m-1">
							<Button variant="primary" className="w-100" onClick={() => setModalVisible(true)}>Создать</Button>
						</div>
						<Form action="/logout/" method="POST" className="m-1">
							<Button type="submit" variant="dark" className="w-100">Выйти</Button>
						</Form>
					</Card.Body>
				</Card>
			</aside>
		</>
	);
}
