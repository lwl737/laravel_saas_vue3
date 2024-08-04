import { ElMessage } from "element-plus";
import getFile from "@/utils/getFile";
import { fileFirst, schedule } from "@/api/modules/upload";
import { Upload } from "@/api/interface/upload";
import { StStore } from "@/stores/modules/sts";
import { ref, computed, watch } from "vue";
import { fileUnit } from "@/utils/util";
import OSS from "ali-oss";
import { ResultEnum } from "@/enums/httpEnum";
type FileLists = {
  file_name: string;
  file_type: string;
  size: number;
  sizeText: string;
  file: File | undefined;
  dir_id: string;
  dir_link: string;
};
function init(
  uploadSuccFunc: undefined | (() => void) = undefined,
  completeFunc: undefined | ((errorIdx: number[], successIdx: number[]) => void) = undefined
) {
  const stStore = StStore();

  const fileLists = ref<Array<{ fileList: FileList; dir_id: string; dir_link: string }>>([]);

  const percentageArr = ref<number[]>([]);

  const errorArr = ref<string[]>([]);

  const cptArr = ref<any[]>([]); //断点值

  watch(
    () => fileLists.value.length,
    () => {
      processTasks().start();
    }
  );

  const delFile = (file_url: string) => {
    getClient()?.delete(file_url);
  };

  const getClient = () => {
    if (!oss_config_ref.value) return undefined;
    return new OSS({
      // yourRegion填写Bucket所在地域。以华东1（杭州）为例，Region填写为oss-cn-hangzhou。
      region: "oss-cn-" + oss_config_ref.value.oss_endpoint.split(".").at(0),
      // 从STS服务获取的临时访问密钥（AccessKey ID和AccessKey Secret）。
      accessKeyId: oss_config_ref.value.accessKeyId,
      accessKeySecret: oss_config_ref.value.accessKeySecret,
      // 从STS服务获取的安全令牌（SecurityToken）。
      stsToken: oss_config_ref.value.securityToken,
      // 填写Bucket名称。
      bucket: oss_config_ref.value.oss_bucket
    });
  };

  const oss_config_ref = ref<Upload.File.OssConfig | undefined>();

  stStore.loadConfig(oss_config => {
    oss_config_ref.value = oss_config;
  });
  const upload = (dir_id: string, dir_link: string, selAfterFunc: undefined | (() => void) = undefined) => {
    getFile(
      (file: File) => {
        if (stStore.uploadConfigGet === undefined) return false;
        else if (stStore.uploadConfigGet.upload_check_file_type === 0) return true;
        else {
          if (!stStore.uploadConfigGet.upload_file_type) throw "上传文件失败";
          else if (
            stStore.uploadConfigGet.upload_file_type.findIndex(item => item.type.split(",").indexOf(file.type) !== -1) === -1
          )
            throw `暂不支持${file.name}的文件类型上传`;
        }
        return true;
      },
      stStore.uploadConfigGet?.upload_max_size,
      {
        successResult: async (fileList: FileList) => {
          if (selAfterFunc) selAfterFunc();
          fileLists.value.push({ fileList: fileList, dir_id, dir_link });
        },
        errorResult: (msg: string) => {
          ElMessage.error(msg);
        }
      },
      true,
      stStore.capacityGet !== undefined && stStore.maxCapacityGet !== undefined
        ? {
            now: stStore.capacityGet,
            max: stStore.maxCapacityGet
          }
        : undefined
    );
  };

  const uploadList = computed(() => {
    let fileArr: FileLists[] = [];

    fileLists.value.forEach(item => {
      for (let i = 0; i < item.fileList.length; i++) {
        fileArr.push({
          file_name: item.fileList[i].name,
          file_type: item.fileList[i].type.split("/")[0],
          size: item.fileList[i].size,
          file: item.fileList[i],
          dir_id: item.dir_id,
          dir_link: item.dir_link,
          sizeText: fileUnit(item.fileList[i].size)
        });
      }
    });

    return fileArr;
  });

  const isRunning = ref<boolean>(false);

  const startIndex = ref<number>(0);

  const errorIdx = ref<number[]>([]);
  const successIdx = ref<number[]>([]);

  function processTasks() {
    return {
      start: async () => {
        if (isRunning.value) return;

        isRunning.value = true;

        while (startIndex.value <= uploadList.value.length - 1) {
          if (uploadList.value[startIndex.value].file !== undefined) {
            var status = await sliceUpload(
              uploadList.value[startIndex.value].file as File,
              uploadList.value[startIndex.value].dir_id,
              uploadList.value[startIndex.value].dir_link
            );
          }

          if (!status) errorIdx.value.push(startIndex.value); //错误
          startIndex.value++;
        }

        if (completeFunc !== undefined) completeFunc(errorIdx.value, successIdx.value);

        errorIdx.value = [];

        successIdx.value = [];

        isRunning.value = false;
      }
    };
  }

  const upload_slice_size = computed(() => {
    return (stStore.uploadConfigGet?.upload_slice_size ? stStore.uploadConfigGet?.upload_slice_size : 1024) * 1024;
  });

  function sliceUpload(file: File, dir_id: string, dir_link: string) {
    return new Promise(async (resolve, reject) => {
      let client = getClient();
      if (client) {
        fileFirst({
          dir_id: dir_id, //文件夹id
          dir_link: dir_link, //文件夹id
          file_size: file.size, //文件大小
          file_name: file.name, //文件名称
          file_type: file.type //文件类型
        })
          .catch(error => {
            console.log("error");
            setTimeout(() => resolve(sliceUpload(file, dir_id, dir_link)), 5000); //5秒钟再重试一次
          })
          .then(res => {
            if (!res) return;

            if (res.errCode !== ResultEnum.SUCCESS) {
              errorArr.value[startIndex.value] = res.msg;
              resolve(false);
              return;
            }

            let { file_url, oid } = res.data;
            let options = {
              // 获取分片上传进度、断点和返回值。
              progress: async (p: any, cpt: any, res: any) => {
                if (p === 1) {
                  await schedule({ oid: oid, schedule: p });
                  resolve(true);
                  if (uploadSuccFunc) uploadSuccFunc();
                }
                percentageArr.value[startIndex.value] = Number((p * 100).toFixed(0));
                cptArr.value[startIndex.value] = cpt; //记录断点值
              },
              // 设置并发上传的分片数量。
              parallel: 1,
              // 设置分片大小。默认值为1 MB，最小值为100 KB。
              partSize: upload_slice_size.value,
              // headers,
              // 自定义元数据，通过HeadObject接口可以获取Object的元数据。
              // meta: {  uuid  },
              mime: "text/plain"
            };

            let restartNum = 0;

            const multipartUpload = () => {
              if (client) {
                client
                  .multipartUpload(file_url, file, {
                    ...options,
                    checkpoint: cptArr.value[startIndex.value] ? cptArr.value[startIndex.value] : undefined
                    // 设置上传回调。
                    // 如果不涉及回调服务器，请删除callback相关设置。
                  })
                  .catch(error => {
                    //阿里那边上传错误
                    ++restartNum;
                    console.log("上传失败,重试次数" + restartNum);
                    // resolve("wait");
                    setTimeout(() => multipartUpload(), 5000); //5秒钟再重试一次
                  });
              }
            };

            multipartUpload();
          });
      }
    });
  }

  return { upload, uploadList, percentageArr, errorArr, delFile };
}

export default init;
