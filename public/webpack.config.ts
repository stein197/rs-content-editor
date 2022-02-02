import path from "path";

export default {
	entry: path.resolve(__dirname, "index.tsx"),
	output: {
		filename: "index.js",
		path: __dirname
	},
	mode: "production",
	optimization: {
		minimize: false
	},
	resolve: {
		extensions: [
			".js",
			".ts",
			".tsx"
		],
		modules: [
			path.resolve(__dirname, "node_modules"),
			path.resolve(__dirname, "src")
		]
	},
	module: {
		rules: [
			{
				test: /\.tsx?$/,
				use: "ts-loader",
				exclude: /node_modules/
			},
			{
				// test: /\.(?:j|t)s(?:x)?$/,
				test: /\.tsx?$/,
				resolve: {
					fullySpecified: false
				}
			}
		]
	}
};
