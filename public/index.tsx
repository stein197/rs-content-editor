import * as ReactDOM from "react-dom";
import * as React from "react";
import App from "App";
import "bootstrap/dist/css/bootstrap.min.css";

document.addEventListener("DOMContentLoaded", () => {
	ReactDOM.render(<App/>, document.body.querySelector("main"));
});
