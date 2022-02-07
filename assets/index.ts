import * as ReactDOM from "react-dom";
import * as React from "react";
import App from "App";

document.addEventListener("DOMContentLoaded", () => {
	ReactDOM.render(React.createElement(App), document.body.querySelector("main"));
});
