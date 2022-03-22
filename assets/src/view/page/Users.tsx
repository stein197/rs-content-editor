import React from "react";
import {Container, Card} from "react-bootstrap";
import Header from "view/Header";
import Fetch from "view/Fetch";
import DataTable from "view/DataTable";
import NotFound from "view/page/NotFound";
import URL from "API/URL";

export default function Users(): JSX.Element {
	return (
		<Fetch input="/api/users/current/">
			{(response, data) => (
				data.admin > 0 ? (
					<>
						<Container className="py-1">
							<Header/>
						</Container>
						<Container className="py-1">
							<Card>
								<Card.Body>
									<Fetch input={URL.Users}>
										{(response, data) => (
											<DataTable props={[
												{
													name: "name",
													type: "string"
												},
												{
													name: "password",
													type: "string"
												},
												{
													name: "admin",
													type: "boolean"
												}
											]} propsUrl="" crudUrl="/api/users/" data={data} actions={["create", "delete"]}/>
										)}
									</Fetch>
								</Card.Body>
							</Card>
						</Container>
					</>
				) : (
					<NotFound/>
				)
			)}
		</Fetch>
	);
}
