import path from "path";

const DIR_ASSETS: string = path.resolve(__dirname, "assets");
const DIR_PUBLIC: string = path.resolve(__dirname, "public");

export default (env: {dev?: boolean}) => ({
	entry: path.resolve(DIR_ASSETS, "index.ts"),
	output: {
		filename: "index.js",
		path: DIR_PUBLIC
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
			path.resolve(DIR_ASSETS, "src")
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
			}
		]
	}
});
