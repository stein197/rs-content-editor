import React from "react";
import {Container, Row, Col, Tabs, Tab} from "react-bootstrap";
import Header from "view/Header";
import Sidebar from "view/Sidebar";
import Content from "view/Content";
import Fetch from "view/Fetch";
import DataTable from "view/DataTable";
import {useParams} from "react-router-dom";

export default function Type(): JSX.Element {
	const params = useParams();
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
						<Content>
							<Fetch input={`/api/type/${params.typeID}/`}>
								{(typeResponse, typeData) => (
									typeResponse.ok ? (
										<>
											<h1>{typeData.name}</h1>
											<Tabs defaultActiveKey="items">
												<Tab eventKey="items" title="Данные">
													<Fetch input={`/api/type/${params.typeID}/entities/`}>
														{(entitiesResponse, entitiesData) => (
															<DataTable crudUrl={`/api/type/${params.typeID}/`} propsUrl={`/api/type/${params.typeID}/props/`} data={entitiesData} actions={["create", "delete", "edit"]}/>
														)}
													</Fetch>
												</Tab>
												<Tab eventKey="itemProperties" title="Свойства записей">
													<Fetch input={`/api/type/${params.typeID}/props/`}>
														{(propsResponse, propsData) => (
															<DataTable propsUrl="" data={propsData} actions={["create", "delete", "edit"]}/>
														)}
													</Fetch>
												</Tab>
												<Tab eventKey="typeProperties" title="Свойства типа">
													<DataTable propsUrl="" data={typeData.properties ? Object.entries(typeData.properties).map((entry: any) => ({
														name: entry[0],
														value: entry[1]
													})) : []} actions={["create", "delete"]}/>
												</Tab>
												<Tab eventKey="settings" title="Настройки"></Tab>
											</Tabs>
										</>
									) : (
										<h2>{typeData.error.message}</h2>
									)
								)}
							</Fetch>
						</Content>
					</Col>
				</Row>
			</Container>
		</>
	);
}
