import React from "react";
import {Table, Button, Modal, Form} from "react-bootstrap";
import EntityTypeValue from "view/EntityTypeValue";
import {onFileInputChange, loadFile} from "Util";

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
	const onModalActionClick = async () => {
		if (action === Action.Delete) {
			fetch(props.crudUrl + `${props.id}/`, {
				method: "DELETE"
			}).then(response => {
				if (response.ok) {
					setModalVisible(false);
					setDeleted(true);
				}
			});
		} else if (action === Action.Edit) {
			if (ggRef) {
				const result: any = {};
				for (const tr of Array.from(ggRef.querySelector("tbody").children)) {
					const input = (tr as HTMLElement).querySelector("input");
					if (input) {
						const inputType = input.getAttribute("type");
						result[input.getAttribute("name")!.toString()] = !inputType || inputType === "text" ? (
							input!.value
						) : inputType === "number" ? (
							+input!.value
						) : inputType === "checkbox" ? (
							input.checked
						) : inputType === "file" ? (
							{
								name: input.files![0].name,
								data: btoa((await loadFile(input))!.toString())
							}
						) : (
							input!.value
						);
					} else {
						const selectList = (tr as HTMLElement).querySelectorAll("select");
						for (const select of selectList) {
							const selectName = select.getAttribute("name")!.toString();
							if (selectName.match(/\[.+?\]$/)) {
								const realName = selectName.replace(/\[.+?\]$/, "");
								result[realName] = result[realName] ? result[realName] + `:${select.value}` : select.value;
							} else {
								result[selectName] = select.value;
							}
						}
					}
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
												{typeof prop[1] === "boolean" ? (
													<input className="form-check-label" type="checkbox" defaultChecked={prop[1]} name={prop[0]}/>
												) : props.propTypes?.find(p => p.name === prop[0])?.type === "date" ? (
													<input className="form-check-label" type="date" defaultValue={prop[1]} name={prop[0]}/>
												) : props.propTypes?.find(p => p.name === prop[0])?.type === "entity" ? (
													<EntityTypeValue name={props.propTypes?.find(p => p.name === prop[0])!.name} value={prop[1]}/>
												) : props.propTypes?.find(p => p.name === prop[0])?.type === "file" ? (
													<div>
														<img src={(() => {
															if (!prop[1])
																return "";
															const format: string | null = props.propTypes.find(p => p.name === prop[0]).format;
															const [dir, , displayFormat] = format ? format.split(";") : ["/media/", "", "{name}.{ext}"];
															const [fileName, fileExt] = prop[1].split(".");
															return `${dir}/${displayFormat.replace("{name}", fileName).replace("{ext}", fileExt)}`;
														})()}/>
														<input className="custom-file-input" type="file" name={prop[0]} onChange={onFileInputChange}/>
													</div>
												) : (
													<Form.Control type={typeof prop[1] === "number" ? "number" : "text"} disabled={prop[0] === "id"} placeholder={prop[0]} name={prop[0]} defaultValue={prop[1] == null ? "" : typeof prop[1] === "object" ? JSON.stringify(prop[1]) : prop[1]}/>
												)}
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
					<td key={col}>
						{eProps[col] == null ? (
							""
						) : typeof eProps[col] === "object" ? (
							JSON.stringify(eProps[col])
						) : typeof eProps[col] === "boolean" ? (
							<input className="form-check-label" type="checkbox" defaultChecked={eProps[col]} disabled/>
						) : props.propTypes?.find(p => p.name === col)?.type === "file" ? (
							<img src={(() => {
								if (!eProps[col])
									return "";
								const format: string | null = props.propTypes.find(p => p.name === col).format;
								const [dir, , displayFormat] = format ? format.split(";") : ["/media/", "", "{name}.{ext}"];
								const [fileName, fileExt] = eProps[col].split(".");
								return `${dir}/${displayFormat.replace("{name}", fileName).replace("{ext}", fileExt)}`;
							})()}/>
						) : (
							eProps[col]
						)}
					</td>
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
	propTypes?: any[];
	crudUrl: string;
}
