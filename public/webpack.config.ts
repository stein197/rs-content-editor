import path from "path";

export default (env: {dev?: boolean}) => ({
	entry: path.resolve(__dirname, "index.tsx"),
	output: {
		filename: "index.js",
		path: __dirname
	},
	mode: env.dev ? "development" : "production",
	optimization: {
		minimize: !env.dev
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
				exclude: /node_modules/,
				resolve: {
					fullySpecified: false
				}
			},
			{
				test: /\.css$/,
				use: [
					"style-loader",
					"css-loader"
				]
			}
		]
	}
});
