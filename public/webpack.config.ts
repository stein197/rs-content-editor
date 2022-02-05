import path from "path";

export default (env: {dev?: boolean}) => ({
	entry: path.resolve(__dirname, "index.ts"),
	output: {
		filename: "index.js",
		path: __dirname
	},
	mode: env.dev ? "development" : "production",
	devtool: env.dev ? "source-map" : false,
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
				use: [
					{
						loader: "ts-loader",
						options: {
							configFile: path.resolve(__dirname, env.dev ? "tsconfig.default.json" : "tsconfig.json")
						}
					}
				],
				exclude: /node_modules/,
				resolve: {
					fullySpecified: false
				},
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
