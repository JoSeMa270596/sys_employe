import { StatusEnum } from "../../../shared/models/status-enum";

export interface Employee {
  id: number;
  user_id: number;
  first_name: string;
  last_name: string;
  department_id: number;
  hire_date: string;
  status: StatusEnum;
  created_at?: string;
  updated_at?: string;
}
