import React from "react";
import {HashRouter, Routes, Route} from "react-router-dom";
import Index from "view/page/Index";
import NotFound from "view/page/NotFound";

export default function App(): JSX.Element {
	return window.location.pathname === "/" ? (
		<HashRouter>
			<Routes>
				<Route index element={<Index/>}/>
				<Route path="*" element={<NotFound/>}/>
			</Routes>
		</HashRouter>
	) : (
		<NotFound/>
	);
}
