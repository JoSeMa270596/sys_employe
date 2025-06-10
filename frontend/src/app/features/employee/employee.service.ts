import { HttpClient, HttpParams } from '@angular/common/http';
import { inject, Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiResponse } from '../../shared/models/api-response';
import { Employee } from './models/employee';
import { CreateEmployeeDto } from './models/create-employee-dto';
import { UpdateEmployeeDto } from './models/update-employee-dto';

@Injectable({
  providedIn: 'root'
})
export class EmployeeService {
  #apiUrl = 'http://localhost:8000/api';
  #httpClient = inject(HttpClient);

  getEmployees(filters?: {
    name?: string;
    department_id?: number;
    status?: 'activo' | 'inactivo';
    hire_date?: string;
    page?: number;
  }): Observable<ApiResponse<Employee[]>> {
    let params = new HttpParams();

    if (filters) {
      if (filters.name) params = params.append('name', filters.name);
      if (filters.department_id) params = params.append('department_id', filters.department_id.toString());
      if (filters.status) params = params.append('status', filters.status);
      if (filters.hire_date) params = params.append('hire_date', filters.hire_date);
      if (filters.page) params = params.append('page', filters.page.toString());
    }

    return this.#httpClient.get<ApiResponse<Employee[]>>(`${this.#apiUrl}/employees`, { params });
  }

  getEmployee(id: number): Observable<Employee> {
    return this.#httpClient.get<Employee>(`${this.#apiUrl}/employees/${id}`);
  }

  createEmployee(employee: CreateEmployeeDto): Observable<Employee> {
    return this.#httpClient.post<Employee>(`${this.#apiUrl}/employees`, employee);
  }

  updateEmployee(employee: UpdateEmployeeDto & { id: number }): Observable<Employee> {
    return this.#httpClient.put<Employee>(`${this.#apiUrl}/employees/${employee.id}`, employee);
  }

  deleteEmployee(id: number): Observable<{ message: string }> {
    return this.#httpClient.delete<{ message: string }>(`${this.#apiUrl}/employees/${id}`);
  }
}
