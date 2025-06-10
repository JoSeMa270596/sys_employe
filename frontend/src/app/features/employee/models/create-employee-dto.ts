import { StatusEnum } from "../../../shared/models/status-enum";

export interface CreateEmployeeDto {
  user_id: number;
  first_name: string;
  last_name: string;
  department_id: number;
  hire_date: string;
  status?: StatusEnum;
}
