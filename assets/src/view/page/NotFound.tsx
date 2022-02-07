import React from "react";
import {Container} from "react-bootstrap";

export default function NotFound(): JSX.Element {
	return (
		<div className="min-vh-100 d-flex align-items-center justify-content-center">
			<Container className="text-center fs-1">
				<p className="fw-bold">NOT FOUND</p>
				<p className="fw-bold">404</p>
			</Container>
		</div>
	);
}
