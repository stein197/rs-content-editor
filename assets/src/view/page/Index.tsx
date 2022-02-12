import React from "react";
import {Container, Row, Col} from "react-bootstrap";
import Header from "view/Header";
import Sidebar from "view/Sidebar";
import Content from "view/Content";

export default function Index(): JSX.Element {
	return (
		<>
			<Container className="py-1">
				<Header/>
			</Container>
			<Container className="py-1">
				<Row className="mt-4">
					<Col xs={12} md={4} xl={3}>
						<Sidebar></Sidebar>
					</Col>
					<Col xs={12} md={8} xl={9}>
						<Content></Content>
					</Col>
				</Row>
			</Container>
		</>
	);
}
