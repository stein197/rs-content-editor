import React from "react";
import {Button, Form, Card} from "react-bootstrap";
import {Link} from "react-router-dom";
import Fetch from "view/Fetch";
import URL from "API/URL";

export default function Header(): JSX.Element {
	return (
		<Fetch input={URL.User}>
			{React.useCallback((response, data) => (
				<header>
					<Card>
						<Card.Body className="d-flex flex-row justify-content-end">
							<Button variant="primary" className="m-1">
								<Link to="/" className="text-white">Главная</Link>
							</Button>
							<Button variant="primary" className="m-1">
								<Link to="/import/" className="text-white">Импорт</Link>
							</Button>
							<Button variant="primary" className="m-1">
								<Link to="/export/" className="text-white">Экспорт</Link>
							</Button>
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
	);
}
