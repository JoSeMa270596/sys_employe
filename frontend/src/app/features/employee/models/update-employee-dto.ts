import { StatusEnum } from "../../../shared/models/status-enum";

export interface UpdateEmployeeDto {
  first_name?: string;
  last_name?: string;
  department_id?: number;
  hire_date?: string;
  status?: StatusEnum;
}
