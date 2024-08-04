interface Clourse {
	errorResult?: (msg: string) => void;
	successResult: (file: any) => void;
}
type CheckType = Array<string> | string | ((file: File) => boolean | void) | undefined;

/**
 * @description: 上传文件
 * @param {Array} checkType
 * @param {number} checkSize  kb多少
 * @return {*}
 */
const uploads = (
	checkType: CheckType = undefined,
	checkSize: number | undefined = undefined,
	clourse: Clourse,
	multiple: boolean = false,
	capacity:{max:number,now:number}|undefined = undefined,
) => {
	//在body中添加标签
	//添加div元素(在内存中)
	let divObj = document.createElement("input");
	divObj.type = "file";
	divObj.multiple = multiple;
	divObj.style.display = "none";
	divObj.onchange = file => getFile(file, divObj, checkType, checkSize, clourse, multiple,capacity);
	document.body.appendChild(divObj);
	divObj.click();
};

const type: { [key: string]: Array<string> } = {
	image: ["png", "jpg", "jpeg"],
	excel: ["xls", "xlsx"]
};

const getFile = (
	ev: Event,
	divObj: any,
	checkType: CheckType,
	checkSize: number | undefined,
	clourse: Clourse,
	multiple: boolean,
	capacity:{max:number,now:number}|undefined,
) => {
	document.body.removeChild(divObj);
	const files = (ev.target as HTMLInputElement).files as FileList;
  // console.log(files);
	if (typeof checkType === "string") checkType = type[checkType] ? type[checkType] : [];
	if (checkType instanceof Array) checkType = checkType.map(item => item.toLowerCase());

	const checkTypeFunc: (file: File) => boolean | void =
		typeof checkType === "function"
			? checkType
			: (file: File) => {
					if (checkType === undefined) return true;
					else {
						let fileType = file.name.split(".").pop()?.toLowerCase();
						if (!fileType)
							throw (
								(!multiple || files.length === 1 ? "该文件" : files[0].name) +
								"必须为" +
								(checkType as Array<string>).reduce((prev, cur) => prev + "," + cur) +
								"类型"
							);
						else if ((checkType as Array<string>).indexOf(fileType) !== -1) return true;
						else
							throw (
								(!multiple || files.length === 1 ? "该文件" : files[0].name) +
								"必须为" +
								(checkType as Array<string>).reduce((prev, cur) => prev + "," + cur) +
								"类型"
							);
					}
			  };

	 			
	for (let i = 0; i < files.length; i++) {
		let file: File = files[i];
		if( capacity ) capacity.now += file.size;
		try {
			if (!checkTypeFunc(file)) throw `暂不支持上传该文件`;
			// throw (!multiple || files.length === 1 ? "该文件" : files[0].name) + "必须为" + checkType.reduce((prev, cur) => prev + "," + cur) + "类型";

			if (typeof checkSize !== "undefined" && file.size >= checkSize * 1024)
				throw (!multiple || files.length === 1 ? "该文件" : files[0].name) + "不得超过" + fileUnit(checkSize) + "kb";
		} catch (err) {
			if (typeof clourse !== "undefined" && typeof clourse.errorResult !== "undefined") return clourse.errorResult(err as string);
			else throw err;
		}
	}

	if(capacity && capacity.now > capacity.max){
		let err_text =  `上传文件超过最大容量${fileUnit(capacity.max)}，请删除素材库的文件后重新上传`;
		if (typeof clourse !== "undefined" && typeof clourse.errorResult !== "undefined") return clourse.errorResult(err_text as string);
		else throw err_text;
	}   
	
	return clourse.successResult(multiple ? files : files[0]);
};


function fileUnit(size: number) {
	if (size >= 1024 * 1024 * 1024) return Math.floor(size / 1024 / 1024 / 1024) + 'GB';
	else if (size >= 1024 * 1024) {
		return Math.floor(size / 1024 / 1024) + 'MB';
	} else return Math.floor(size / 1024) + 'KB';
}
export default uploads;
