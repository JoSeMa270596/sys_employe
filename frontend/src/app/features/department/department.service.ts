import { HttpClient } from '@angular/common/http';
import { inject, Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Department } from './models/department';
import { CreateDepartmentDto } from './models/create-department-dto';
import { UpdateDepartmentDto } from './models/update-department-dto';

@Injectable({
  providedIn: 'root'
})
export class DepartmentService {
  #apiUrl = 'http://localhost:8000/api';
  #httpClient = inject(HttpClient);

  getDepartments(): Observable<Department[]> {
    return this.#httpClient.get<Department[]>(`${this.#apiUrl}/departments`);
  }

  getDepartment(id: number): Observable<Department> {
    return this.#httpClient.get<Department>(`${this.#apiUrl}/departments/${id}`);
  }

  createDepartment(department: CreateDepartmentDto): Observable<Department> {
    return this.#httpClient.post<Department>(`${this.#apiUrl}/departments`, department);
  }

  updateDepartment(department: UpdateDepartmentDto & { id: number }): Observable<Department> {
    return this.#httpClient.put<Department>(`${this.#apiUrl}/departments/${department.id}`, department);
  }

  deleteDepartment(id: number): Observable<{ message: string }> {
    return this.#httpClient.delete<{ message: string }>(`${this.#apiUrl}/departments/${id}`);
  }

  assignChief(departmentId: number, chiefEmployeeId: number): Observable<{ message: string }> {
    return this.#httpClient.patch<{ message: string }>(`${this.#apiUrl}/departments/${departmentId}/assign-chief`, { chief_employee_id: chiefEmployeeId });
  }
}
