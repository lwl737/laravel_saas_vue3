import mitt from "mitt";
type Events = {
  loadImageFromFile: File;
  loadImageFromURL: string;
  openThemeDrawer: void;
  uploadFile: { dir_id: string; dir_link: string };
  openImg: { imgArr: Array<{ title: string; url: string }>; showIndex?: number };
  uploadFirstEvent: { file: File };
  uploadSuccess: void;
  openVideo: { src: string };
  scrollTo: { y: number };
};
const mittBus = mitt<Events>();

export default mittBus;
