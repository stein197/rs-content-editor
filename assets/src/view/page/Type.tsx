import React from "react";
import {Container, Row, Col, Tabs, Tab, Table} from "react-bootstrap";
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
										<Fetch input={`/api/type/${params.typeID}/entities/`}>
											{(entitiesResponse, entitiesData) => (
												<>
													<h1>{typeData.name}</h1>
													<Tabs defaultActiveKey="items">
														{!!entitiesData.length && (
															<Tab eventKey="items" title="Данные">
																<DataTable crudUrl={`/api/type/${params.typeID}/`} propsUrl={`/api/type/${params.typeID}/props/`} data={entitiesData} actions={["create", "delete", "edit"]}/>
															</Tab>
														)}
														{!!entitiesData.length && (
															<Tab eventKey="itemProperties" title="Свойства записей">
																<Fetch input={`/api/type/${params.typeID}/props/`}>
																	{(propsResponse, propsData) => (
																		<DataTable props={[
																			{
																				name: "name",
																				type: "string"
																			},
																			{
																				name: "type",
																				type: [
																					"boolean",
																					"number",
																					"string",
																					"json",
																					"date",
																					"file",
																					"entity"
																				]
																			},
																			{
																				name: "required",
																				type: "boolean"
																			},
																			{
																				name: "format",
																				type: "string"
																			}
																		]} propsUrl="" crudUrl={`/api/type/${params.typeID}/props/`} data={propsData} actions={["create", "delete", "edit"]}/>
																	)}
																</Fetch>
															</Tab>
														)}
														{!entitiesData.length && (
															<Tab eventKey="typeProperties" title="Свойства типа">
																<DataTable crudUrl={`/api/type/${params.typeID}/properties/`} propsUrl="" props={[
																	{
																		name: "name",
																		type: "string"
																	},
																	{
																		name: "value",
																		type: "string",
																	},
																	{
																		name: "type",
																		type: [
																			"boolean",
																			"number",
																			"string"
																		]
																	}
																]} data={typeData.properties ? Object.entries(typeData.properties).map((entry: any) => ({
																	id: entry[0],
																	name: entry[0],
																	value: entry[1],
																	type: typeof entry[1]
																})) : []} actions={["create", "delete"]}/>
															</Tab>
														)}
														<Tab eventKey="settings" title="Настройки">
															<Fetch input={`/api/type/${params.typeID}/`}>
																{(typeResponse, typeData) => (
																	<Table>
																		<tbody>
																			<tr>
																				<td>Есть id</td>
																				<td>
																					<input className="form-check-input" type="checkbox" defaultChecked={typeData.hasID} disabled={true}/>
																				</td>
																			</tr>
																			<tr>
																				<td>Инкремент с</td>
																				<td>{typeData.incrementFrom}</td>
																			</tr>
																			<tr>
																				<td>Родитель</td>
																				{typeData.parent ? (
																					<Fetch input={`/api/type/${typeData.parent}/`}>
																						{(parentResponse, parentData) => (
																							<td>{parentData.name}</td>
																						)}
																					</Fetch>
																				) : (
																					<td>Нет</td>
																				)}
																			</tr>
																			<tr>
																				<td>Хранить в родителе</td>
																				<td>
																					<input className="form-check-input" type="checkbox" defaultChecked={typeData.storeInParent} disabled={true}/>
																				</td>
																			</tr>
																		</tbody>
																	</Table>
																)}
															</Fetch>
														</Tab>
													</Tabs>
												</>
											)}
										</Fetch>
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
