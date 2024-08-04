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

  export interface AddReq {
    organi_name: string;
    organi_sort: number;
    organi_pid: number;
  }

  export interface EditReq {
    organi_name: string;
    organi_sort: number;
    organi_id: number;
  }

  export interface DelReq {
    organi_id: number;
  }
}
