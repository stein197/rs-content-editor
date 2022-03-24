export function onFileInputChange(e: any) {
	const img = e.nativeEvent.target.parentElement!.querySelector("img")!!;
	const target = e.nativeEvent.target as HTMLInputElement;
	img.src = URL.createObjectURL(target.files![0]);
}

export async function loadFile(input: HTMLInputElement): Promise<string | ArrayBuffer | null> {
	return new Promise<string | ArrayBuffer | null>(resolve => {
		const reader = new FileReader();
		reader.onload = () => resolve(reader.result);
		reader.readAsBinaryString(input.files![0]);
	});
}
