import { Client } from "basic-ftp";
import path from 'path';
import fs from "fs";
import dotenv from "dotenv";


const config = Object.assign(
    dotenv.config().parsed,
    dotenv.config({ path: path.resolve(process.cwd(), ".env." + process.env.NODE_ENV) }).parsed
);


const upload_dir_path = process.cwd() + (config.UPLOAD_DIR.substring(0, 1) === '/' ? config.UPLOAD_DIR : '/' + config.UPLOAD_DIR);


if (!upload_dir_path  || !fs.existsSync(upload_dir_path)) {
    console.log(upload_dir_path + '文件不存在');
    process.exit();
}

const uploadArr = [];  //要上传的文件 {type:"file"|"dir" , path : ""} 


const readDirectoryContents = (directoryPath) => {
    const files = fs.readdirSync(directoryPath);

    for (const file of files) {
        const filePath = path.join(directoryPath, file);
        const stat = fs.statSync(filePath);

        if (stat.isFile()) {
            // 如果是文件，记录成文件类型
            uploadArr.push({ type: 'file', path: filePath, targetPath: filePath.substring(upload_dir_path.length).replace(/\\/g, "/") });

        } else if (stat.isDirectory()) {
            // 如果是子目录，则递归调用该方法 并记录文件夹类型
            uploadArr.push({ type: 'dir', path: filePath, targetPath: filePath.substring(upload_dir_path.length).replace(/\\/g, "/") });
            readDirectoryContents(filePath);
        }
    }
}


if (fs.statSync(upload_dir_path).isFile()) uploadArr.push({ path: upload_dir_path, type: "file" });
else readDirectoryContents(upload_dir_path);

ftpUpload(uploadArr);



async function ftpUpload(uploadFileArr) {
    const client = new Client();
    client.ftp.verbose = true;

    try {
        await client.access({
            host: config.HOST,
            port: config.PORT,
            user: config.USER,
            password: config.PASSWORD,
            secure: false
        });

        /**  清空工作目录 **/ 
        await client.clearWorkingDir();

        /**  上传工作目录 **/ 
        for (let i = 0; i < uploadFileArr.length ; i++){
            if (uploadFileArr[i].type === 'file') await client.uploadFrom(uploadFileArr[i].path, uploadFileArr[i].targetPath)
            else  if (uploadFileArr[i].type === 'dir'){
                await client.ensureDir(uploadFileArr[i].targetPath)
            }
        }

    }
    catch (err) {
        console.log(err)
    }
    client.close()
}