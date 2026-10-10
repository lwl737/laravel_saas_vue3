export namespace Organi {
  export interface OrganiItem {
    organi_name: string;
    organi_sort: number;
    organi_pid: number;
    organi_id: number;
    children: OrganiItem[];
  }

  export interface OrganiAllRes {
    tree: OrganiItem[];
    organi_name: string;
  }

  

  export interface Option {
    tree: {
      organi_name: string;
      organi_pid: number;
      organi_id: number;
      children?: OrganiAllRes["tree"];
    }[];
  }

  export interface OptionReq{
    tenant_id:number;
  }
}
