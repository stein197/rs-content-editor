import React from "react";
import Then from "view/flow/Then";
import Else from "view/flow/Else";

export default (props: {test: boolean; children: any}) => React.Children.map(props.children, (child) => {
	const isThen = child.type === Then;
	const isElse = child.type === Else;
	if (isThen && props.test || isElse && !props.test || !isThen && !isElse && props.test)
		return child;
});
