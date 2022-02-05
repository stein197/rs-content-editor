import React from "react";
import {Container, Row, Col, Form, Button, Card, Alert} from "react-bootstrap";
import If from "view/flow/If";
import Then from "view/flow/Then";
import Else from "view/flow/Else";
import Fetch from "view/Fetch";

export default function App() {
	return (
		<Fetch input="/api/" json={true}>{(response, json) => (
			<Container>
				<Row className="justify-content-center">
					<Col md={8} lg={6}>
						<If test={response.ok}>
							<Then>
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
							</Then>
							<Else>
								<Alert variant="danger" className="m-1 m-md-3 m-lg-5">
									{json.error.msg}
								</Alert>
							</Else>
						</If>
					</Col>
				</Row>
			</Container>
		)}</Fetch>
	);
}
