import { Upload } from "@/api/interface/upload";
export type BoxStatus = "select" | "batchDel" | "none";
export type WebFileType = "image" | "file" | "video" | "";
export interface ListShow extends Upload.File.ListRes {
  dateChange: string;
  file_name_last: string;
  file_name_before: string;
  cover: string;
  webFileType: WebFileType;
}
