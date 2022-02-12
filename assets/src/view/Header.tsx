import React from "react";
import {Navbar, Nav} from "react-bootstrap";
import {Link, HashRouter} from "react-router-dom"

export default function Header(): JSX.Element {
	return (
		<header>
			<HashRouter>
				<Nav className="justify-content-end">
					<Nav.Item>
						<Nav.Link>
							<Link to="/import/">Импорт</Link>
						</Nav.Link>
					</Nav.Item>
					<Nav.Item>
						<Nav.Link>
							<Link to="/export/">Экспорт</Link>
						</Nav.Link>
					</Nav.Item>
					<Nav.Item>
						<Nav.Link>
							<Link to="/users/">Пользователи</Link>
						</Nav.Link>
					</Nav.Item>
					<Nav.Item>
						<Nav.Link href="/logout/">Выход</Nav.Link>
					</Nav.Item>
				</Nav>
			</HashRouter>
		</header>
	);
}
