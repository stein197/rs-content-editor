import React from "react";
import {Container, Row, Col, Form, Button, Card} from "react-bootstrap";

export default function App(): JSX.Element {
	return (
		<Container>
			<Row className="justify-content-center">
				<Col md={8} lg={6}>
					<Card className="m-1 m-md-3 m-lg-5">
						<Card.Body>
							<Form>
								<Form.Group className="mb-3">
									<Form.Label>Логин</Form.Label>
									<Form.Control type="text" placeholder="Логин" name="login"/>
								</Form.Group>
								<Form.Group className="mb-3">
									<Form.Label>Пароль</Form.Label>
									<Form.Control type="password" placeholder="Пароль" name="password"/>
								</Form.Group>
								<Button variant="primary" type="submit" className="w-100">Войти</Button>
							</Form>
						</Card.Body>
					</Card>
				</Col>
			</Row>
		</Container>
	);
}
