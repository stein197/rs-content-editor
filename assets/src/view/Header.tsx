import React from "react";
import {Button, Form, Card, Modal} from "react-bootstrap";
import {Link} from "react-router-dom";
import Fetch from "view/Fetch";
import URL from "API/URL";

export default function Header(): JSX.Element {
	const [importVisible, setImportVisible] = React.useState(false);
	const onImportClick = React.useCallback(() => {
		setImportVisible(true);
	}, []);
	const onModalCloseClick = React.useCallback(() => {
		setImportVisible(false);
	}, []);
	const onExportClick = React.useCallback(() => {

	}, []);
	
	return (
		<>
			<Modal show={importVisible}>
				<Modal.Header closeButton>
					<Modal.Title>Импорт данных</Modal.Title>
				</Modal.Header>
				<Modal.Body>Внимание! При импорте данных, вся база данных будет очищена</Modal.Body>
				<Modal.Footer>
					<Button variant="primary">
						<label>
							<input type="file" style={{display: "none"}}/>
							<span>Продолжить</span>
						</label>
					</Button>
					<Button variant="danger" onClick={onModalCloseClick}>Закрыть</Button>
				</Modal.Footer>
			</Modal>
			<Fetch input={URL.User}>
				{React.useCallback((response, data) => (
					<header>
						<Card>
							<Card.Body className="d-flex flex-row justify-content-end">
								<Button variant="primary" className="m-1">
									<Link to="/" className="text-white">Главная</Link>
								</Button>
								<Button variant="primary" className="m-1" onClick={onImportClick}>Импорт</Button>
								<Button variant="primary" className="m-1" onClick={onExportClick}>Экспорт</Button>
								{data.admin > 0 && (
									<Button variant="primary" className="m-1">
										<Link to="/users/" className="text-white">Пользователи</Link>
									</Button>
								)}
								<Form action="/logout/" method="POST" className="m-1">
									<Button type="submit" variant="dark">Выйти</Button>
								</Form>
							</Card.Body>
						</Card>
					</header>
				), [])}
			</Fetch>

		</>
	);
}
